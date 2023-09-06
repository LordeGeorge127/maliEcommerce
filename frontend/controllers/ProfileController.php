<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserAddress;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class ProfileController extends  \frontend\base\Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::class,
                'rules'=>[
                    ['actions' => ['index', 'update-account', 'update-address'],
                        'allow' => true,
                        'roles' => ['@'],]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $userAddress = $user->getAddress();
//        echo '<pre>';
//        var_dump($userAddress);
//        echo '</pre>';
//        exit;
        return $this->render('index',[
            'userAddress'=>$userAddress,
            'user'=>$user
        ]);
    }
    public function actionUpdateAddress()
    {
        if (!Yii::$app->request->isAjax)
        {
            throw new ForbiddenHttpException("You are only allowed to make Ajax request");
        }
        $user = \Yii::$app->user->identity;
        $userAddress = $user->getAddress();
//        var_dump($user->getAddress());exit;
        $success = false;
        if ($userAddress->load(\Yii::$app->request->post()) && $userAddress->save()){
//            var_dump($userAddress->load(\Yii::$app->request->post()));exit;
          $success = true;
        }
        return $this->renderAjax('user_address',[
            'userAddress'=>$userAddress,
            'success'=>$success
        ]);
    } public function actionUpdateAccount()
    {
        $user = \Yii::$app->user->identity;
        $userAddress = $user->getAddress();
//        var_dump($user->getAddress());exit;
        $success = false;
        if ($user->load(\Yii::$app->request->post()) && $user->save()){
//            var_dump($userAddress->load(\Yii::$app->request->post()));exit;
          $success = true;
        }
        return $this->renderAjax('user_account',[
            'user'=>$user,
            'success'=>$success
        ]);
    }
}