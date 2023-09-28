<?php
namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends \common\models\LoginForm
{
    /**
     * Finds user by [[username]]
     *
     * @return \common\models\User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = \common\models\User::findOne(['username'=>$this->username,
                'status'=>User::STATUS_ACTIVE,
                'admin'=>1
                ]);
        }

        return $this->_user;
    }
}
