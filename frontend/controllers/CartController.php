<?php

namespace frontend\controllers;

use common\models\CartItems;
use common\models\Product;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
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
                'only' => ['add'],
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
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
        } else {
            $cartItems = CartItems::getItemsForUser(\Yii::$app->user->id);
//            VarDumper::dump($cartItems,20,true);exit;
        }

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
            foreach ($cartItems as $i => $cartItem) {
                if ($cartItem['id'] == $id) {
                    $cartItems[$i]['quantity']++;
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
            if ($cartItem){
                $cartItem->quantity++;
            }else{
                $cartItem = new CartItems();
                $cartItem->product_id = $id;
                $cartItem->user_id = \Yii::$app->user->id;
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
    public function actionDelete($id){
        if (\Yii::$app->user->isGuest)
        {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY,[]);
            foreach ($cartItems as $i => $cartItem){
                if ($cartItem['id'] == $id){
                    array_splice($cartItems,$i,1);//pass array to be modeified,pass index,pass no.of items to be deleted
                    break;
                }
            }
            \Yii::$app->session->set(CartItems::SESSION_KEY,$cartItems);
        }else{
            //delete every record created where product id = id and created_by = id
            CartItems::deleteAll(['product_id'=>$id,'user_id'=>\Yii::$app->user->id]);
        }
         return $this->redirect(['index']);
    }
}