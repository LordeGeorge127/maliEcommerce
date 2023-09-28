<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:html',
            ['attribute' => 'image',
                'format' => 'html',
                'value' => fn() => Html::img($model->getImageUrl(), ['style' => 'width:60px'])
            ],
            'price:currency',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => fn() => Html::tag('span', $model->status ? "Active" : "Draft",
                    [
                        'class' => $model->status ? "badge badge-success" : "badge badge-danger"
                    ])
            ],
            'created_at:datetime',
            'updated_at:datetime',
            'created_by:integer',
            'updated_by:integer',
        ],
    ]) ?>

</div>
