<?php

/** @var yii\web\View $this */
/** @var  \yii\data\ActiveDataProvider $dataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">

            <!-- Header-->
            <header class="bg-dark py-5"">
            <div class=" px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Shop in style</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Shoppers Paradise</p>
                </div>
            </div>
            </header>
                <div class="container px-4 px-lg-5 mt-5">
                        <?php echo \yii\widgets\ListView::widget([
                                'dataProvider'=>$dataProvider,
                            'layout'=>'{summary}<div class="row gx-4 gx-lg-3 row-cols-1 row-cols-md-2 row-cols-xl-4">{items}</div>{pager}',
                            'itemView'=>'_product_item',
                            'itemOptions'=>[
                                    'class'=>'col mb-5 product-item',
                            ],
                        'pager'=>[
                                'class'=>\yii\bootstrap5\LinkPager::class
                        ]
                        ]) ?>
                </div>


    </div>
</div>
