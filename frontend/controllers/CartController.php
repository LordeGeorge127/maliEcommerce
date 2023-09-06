<?php

namespace frontend\controllers;

use common\models\CartItems;
use common\models\Product;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class'=>ContentNegotiator::class,
                'only' => ['add'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $cartItems = CartItems::getItemsForUser(\Yii::$app->user->id);
//        var_dump(CartItems::getItemsForUser(\Yii::$app->user->id));exit;
        return $this->render('index',[
            'items'=>$cartItems
        ]);
    }
    public function actionAdd()
    {
        $id = \Yii::$app->request->post('id');
//        var_dump($id);exit;
        $product = Product::find()->id($id)->published()->one();
        if (!$product){
            throw new NotFoundHttpException("Product not found");
        }
        if (\Yii::$app->user->isGuest)
        {
            //session save
        }
        else{
            $cartItem = new CartItems();
            $cartItem->product_id = $id;
            $cartItem->user_id = \Yii::$app->user->id;
            $cartItem->quantity = 1;
            if ($cartItem->save())
            {
                return [
                    'success'=>true
                ];

            }else{
                return [
                    'success'=>false,
                    'errors'=>$cartItem->errors
                ];
            }
        }
    }
}