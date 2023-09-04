<?php

use common\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'id',
                'contentOptions' => ['style' => 'width:10px']
            ],
            [
                'attribute' => 'name',
                'content' => function ($model) {
                    return \yii\helpers\StringHelper::truncateWords($model->name, 7);
                }

            ],

            [
                'attribute' => 'Product Image',
                'content' => function ($model) {
                    /** @var Product */
                    return \yii\bootstrap5\Html::img($model->getImageUrl(), [
                        'style' => 'width:50px'
                    ]);
                }
            ],
//            [
//                    'attribute'=>'price',
//                'content'=>function($model){
//                    return Yii::$app->formatter->asCurrency($model->price, 'USD');
//                },
//                'format'=>'raw'
//            ],
            'price:currency',
            [
                'attribute' => 'status',
                'content' => function ($model) {
                    return Html::tag('span', $model->status ? "Active" : "Draft", [
                        'class' => $model->status ? "text-center badge badge-success" : "badge badge-danger"
                    ]);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime'],
                'contentOptions' => ['style' => 'whitespace:nowrap']
            ], [
                'attribute' => 'updated_at',
                'format' => ['datetime'],
                'contentOptions' => ['style' => 'whitespace:nowrap']
            ],
            //'created_by',
            //'updated_by',
            [
                'class' => ActionColumn::class,
                'template' => '{view},{update},{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('View',
                            ['/product/view', 'id' => $model->id], ['class' => 'btn-sm btn-secondary']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('update',
                            ['/product/update', 'id' => $model->id], ['class' => 'btn-sm btn-primary']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('delete',
                            ['/product/delete', 'id' => $model->id],
                            ['class' => 'btn-sm btn-danger',
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => 'Are you sure you want to delete this item?',
                                ]
                            ],
                        );

                    },

                ]
            ]
        ],
    ]); ?>


</div>
