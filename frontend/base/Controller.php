<?php

namespace frontend\base;

use common\models\CartItems;

class Controller extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY,[]);
            $sum = 0;
            foreach ($cartItems as $cartItem) {
                $sum += $cartItem['quantity'];
            }
        }else{
            $sum = CartItems::findBySql("
        SELECT SUM(quantity) FROM cart_items WHERE user_id=:userId
        ", ['userId' => \Yii::$app->user->id])->scalar();
        }
        $this->view->params['cartItemCount'] = $sum;
        return parent::beforeAction($action);
    }
}