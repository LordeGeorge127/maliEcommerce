<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\order $model */
$orderAddress = $model->orderAddress;
$this->title ='Order: #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <br>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'total_price',
            'status:OrderStatus',
            'firstname',
            'lastname',
            'email:email',
            'transaction_id',
            'paypal_order_id',
            'created_at:datetime',
            ],
    ]) ?>

    <h4>Address</h4>
    <?= DetailView::widget([
        'model' => $orderAddress,
        'attributes' => [
            'address',
            'city',
            'state',
            'country',
            'zipcode',
        ],
    ]) ?>
    <br>
    <h4>Order Items</h4>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Price</th>
        </tr>
        </thead>
        <?php foreach ($model->orderItems as $item): ?>
        <tbody>
        <tr>
            <td><img src="<?php echo $item->product->getImageUrl() ?>" style="width: 50px;"></td>
            <td><?php echo $item->product_name ?></td>
            <td><?php echo $item->quantity ?></td>
            <td><?php echo Yii::$app->formatter->asCurrency( $item->unit_price) ?></td>
            <td><?php echo Yii::$app->formatter->asCurrency($item->quantity * $item->unit_price) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>