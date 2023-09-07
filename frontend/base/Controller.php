<?php

namespace frontend\base;

use common\models\CartItems;

class Controller extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        $this->view->params['cartItemCount'] = CartItems::getTotalQuantityForUser(\Yii::$app->user->id);
        return parent::beforeAction($action);
    }
}