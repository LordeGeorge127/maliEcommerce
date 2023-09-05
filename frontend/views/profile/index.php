<?php
/**
 * @var User $user
 * @var UserAddress[] $userAddress
 * @var \yii\web\View $this
 */

use common\models\User;
use common\models\UserAddress;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class=" mt-1">Address Information</h5>
            </div>
            <div class="card-body">
                <?php \yii\widgets\Pjax::begin([
                    'enablePushState' => false
                ]) ?>
                <?php echo $this->render('user_address', [
                    'userAddress' => $userAddress,
                ]) ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mt-1">Account Information</h5>
            </div>
            <div class="card-body">
                <?php \yii\widgets\Pjax::begin([
                    'enablePushState' => false
                ]) ?>
                <?php echo $this->render('user_account', [
                    'user' => $user
                ]) ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div>
        </div>
    </div>
</div>