<?php

use common\models\order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id' => 'OrdersTable',
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width:60px']
            ],
            ['attribute' => 'fullname',
                'content' => function ($model) {
                    return $model->firstname . ' ' . $model->lastname;
                }],
            'total_price:currency',
            'status:OrderStatus',
            //'email:email',
            //'transaction_id',
            //'paypal_order_id',
            'created_at:datetime',
            'created_by:integer',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}'

            ],
        ],
    ]); ?>


</div>
