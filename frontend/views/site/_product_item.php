<?php
/** @var $model Product */

use common\models\Product;

?>

<div class="card h-100">
    <!-- Sale badge-->
    <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
    <!-- Product image-->
    <img class="card-img-top lg" src="<?php echo $model->getImageUrl() ?>" style="height: 350px;" alt="..."/>
    <!-- Product details-->
    <div class="card-body p-4">
        <div class="text-center">
            <!-- Product name-->
            <h5 class="fw-bolder"><?php echo $model->name ?></h5>
            <div class="d-flex justify-content-center small text-muted mb-2">
                <?php echo $model->getShortDescription() ?>
            </div>
            <!-- Product price-->
            <span>
                   <?php echo Yii::$app->formatter->asCurrency($model->price)
                   ?>
            </span>
        </div>
    </div>
    <!-- Product actions-->
    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
        <a href="#"
           class="btn btn-outline-dark mt-auto btn-add-to-cart">
            Add to cart</a>
    </div>

</div>

