<?php

namespace frontend\controllers;

use common\models\CartItems;
use common\models\Order;
use common\models\OrderAddress;
use common\models\Product;
use common\models\User;
use imanilchaudhari\CurrencyConverter\CurrencyConverter;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CartController extends \frontend\base\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::class,
                'only' => ['add', 'change-quantity', 'create-order', 'submit-payment'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $cartItems = CartItems::getItemsForUser(\Yii::$app->user->id);
//        var_dump(CartItems::getItemsForUser(\Yii::$app->user->id));exit;
        return $this->render('index', [
            'items' => $cartItems
        ]);
    }

    public function actionAdd()
    {
        $id = \Yii::$app->request->post('id');
//        var_dump($id);exit;
        $product = Product::find()->id($id)->published()->one();

        if (!$product) {
            throw new NotFoundHttpException("Product not found");
        }
        if (\Yii::$app->user->isGuest) {

            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
            $found = false;
            foreach ($cartItems as $i => $cartItem) { //or $cartitems as &$item
                if ($cartItem['id'] == $id) {  //same
                    $cartItems[$i]['quantity']++;//$item['quantity']++
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cartItem = [
                    'id' => $id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'quantity' => 1,
                    'total_price' => $product->price
                ];
                $cartItems[] = $cartItem;
            }
            \Yii::$app->session->set(CartItems::SESSION_KEY, $cartItems);

        } else {
            $userId = \Yii::$app->user->id;
            $cartItem = CartItems::find()->userId($userId)->productId($id)->one();
            if ($cartItem) {
                $cartItem->quantity++;
            } else {
                $cartItem = new CartItems();
                $cartItem->product_id = $id;
                $cartItem->user_id = $userId;
                $cartItem->quantity = 1;
            }

            if ($cartItem->save()) {
                return [
                    'success' => true
                ];

            } else {
                return [
                    'success' => false,
                    'errors' => $cartItem->errors
                ];
            }
        }
    }

    public function actionChangeQuantity()
    {
        $id = \Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if (!$product) {
            throw new NotFoundHttpException("Product does not exist");
        }
        $quantity = \Yii::$app->request->post('quantity');
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
            foreach ($cartItems as &$cartItem) {
                if ($cartItem['id'] === $id) {
                    $cartItem['quantity'] = $quantity;
                    break;
                }
            }
            \Yii::$app->session->set(CartItems::SESSION_KEY, $cartItems);
        } else {
            $cartItem = CartItems::find()->userId(\Yii::$app->user->id)->productId($id)->one();
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }
        return [
            'quantity' => CartItems::getTotalQuantityForUser(\Yii::$app->user->id),
            'price' => CartItems::getTotalPriceForItemForUser($id, \Yii::$app->user->id)
        ];
    }

    public function actionDelete($id)
    {
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
            foreach ($cartItems as $i => $cartItem) {
                if ($cartItem['id'] == $id) {
                    array_splice($cartItems, $i, 1);//pass array to be modeified,pass index,pass no.of items to be deleted
                    break;
                }
            }
            \Yii::$app->session->set(CartItems::SESSION_KEY, $cartItems);
        } else {
            //delete every record created where product id = id and created_by = id
            CartItems::deleteAll(['product_id' => $id, 'user_id' => \Yii::$app->user->id]);
        }
        return $this->redirect(['index']);
    }

    public function actionCheckout()
    {
        $cartItems = CartItems::getItemsForUser(Yii::$app->user->id);
        $totalPrice = CartItems::getTotalPriceForUser(Yii::$app->user->id);
        $productQuantity = CartItems::getTotalQuantityForUser(Yii::$app->user->id);
        if (empty($cartItems)) {
            return $this->redirect([Yii::$app->homeUrl]);
        }
//        VarDumper::dump($productQuantity,10,true);
//        VarDumper::dump($totalPrice,10,true);exit;
        $order = new Order();

        $order->status = Order::STATUS_DRAFT;
        $order->total_price = $totalPrice;
        $order->created_at = time();
        $order->created_by = Yii::$app->user->id;
        $transaction = Yii::$app->db->beginTransaction();
        if ($order->load(Yii::$app->request->post())
            && $order->save()
            && $order->saveOrderAddress(Yii::$app->request->post())
            && $order->saveOrderItems()
        ) {
            $transaction->commit();
            CartItems::clearCartItems(Yii::$app->user->id);
//cart items have been cleared->get items from order
            return $this->render('pay-now', [
                'order' => $order,

            ]);
        }

        $orderAddress = new OrderAddress();
        if (!Yii::$app->user->isGuest) {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $userAddress = $user->getAddress();
//           VarDumper::dump($userAddress,20,true);exit;
            $order->firstname = $user->firstname;
            $order->lastname = $user->lastname;
            $order->email = $user->email;
            $order->status = Order::STATUS_DRAFT;

            $orderAddress->address = $userAddress->address;
            $orderAddress->city = $userAddress->city;
            $orderAddress->state = $userAddress->state;
            $orderAddress->country = $userAddress->country;
            $orderAddress->zipcode = $userAddress->zipcode;

        }

        return $this->render('checkout', [
            'order' => $order,
            'orderAddress' => $orderAddress,
            'cartItems' => $cartItems,
            'productQuantity' => $productQuantity,
            'totalPrice' => $totalPrice
        ]);
    }

    public function actionSubmitPayment($orderId)
    {
        $where = ['id' => $orderId, 'status' => Order::STATUS_DRAFT];
        if (!\Yii::$app->user->isGuest) {
            $where['created_by'] = \Yii::$app->user->id;
        }
        $order = Order::findOne($where);
//                VarDumper::dump($order,30,true);exit;

        if (!$order) {
            throw new NotFoundHttpException("Order does not exists");
        }

        $paypalOrderId = Yii::$app->request->post('orderId');
        $exists = Order::find()->andWhere(['paypal_order_id' => $paypalOrderId])->exists();
//                VarDumper::dump($exists,30,true);exit;

        if ($exists) {
            throw new BadRequestHttpException("Transaction ID already in use");
        }
        $environment = new SandboxEnvironment(Yii::$app->params['paypalClientId'], Yii::$app->params['paypalSecretKey']);
        $client = new PayPalHttpClient($environment);
        $response = $client->execute(new OrdersGetRequest($paypalOrderId));
//        VarDumper::dump($response,30,true);exit;

//        $transactionId = Yii::$app->request->post('transactionId');
        if ($response->statusCode === 200){
            $order->paypal_order_id = $paypalOrderId;
            $order->status = $response->result->status === 'COMPLETED' ? Order::STATUS_COMPLETED : Order::STATUS_FAILED;

            $paidAmount = 0;
            foreach ($response->result->purchase_units as $purchase_unit){
                if ($purchase_unit->amount->currency_code === 'USD'){
                    $paidAmount += $purchase_unit->amount->value;
                }
            }
            if ($paidAmount === $order->total_price && $response->result->status === 'COMPLETED'){
                $order->status = Order::STATUS_COMPLETED;
            }
            $order->transaction_id = $response->result->purchase_units[0]->payments->captures[0]->id;
            if ($order->save()){
                if (!$order->sendEmailToVendor()){
                    \Yii::error("Email to vendor not sent");
                } if (!$order->sendEmailToCustomer()){
                    \Yii::error("Email to customer not sent");
                }
                return[
                    'success'=>true
                ];
            }
            else{
                Yii::error("Order was not saved. .Data:".VarDumper::dumpAsString($order->toArray()).
                '.Errors:' .VarDumper::dumpAsString($order->errors));
            }
        }
        throw new BadRequestHttpException("Could not complete Order");



    }
}