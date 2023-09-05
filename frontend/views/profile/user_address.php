<?php
/**
 * @var $userAddress \common\models\UserAddress
 * @var \yii\web\View $this
 */

use yii\bootstrap5\ActiveForm;

?>
<?php if (isset($success) && $success):?>
<div class="alert alert-success">
    Your address was successfully updated
</div>
<?php endif ?>
<?php $addressForm = ActiveForm::begin([
    'action'=>['/profile/update-address'],
    'options'=>['data-pjax'=>1]
]); ?>
<?= $addressForm->field($userAddress, 'address')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'city')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'state')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'country')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'zipcode')->textInput(['autofocus' => true]) ?>
<button class="btn btn-primary">Update</button>
<?php ActiveForm::end() ?>

