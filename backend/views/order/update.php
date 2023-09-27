<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\order $model */

$this->title = 'Update Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = \yii\bootstrap5\ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList($model->getstatusLabels(),
    ['class'=>'from-control'])?>
    <div class="form-group">
        <?= Html::submitButton('Save',['class'=>'btn btn-success']) ?>
    </div>
    <?php \yii\bootstrap5\ActiveForm::end()?>

</div>
