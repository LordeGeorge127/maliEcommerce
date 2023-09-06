<?php
/**
 * @var array $items
 */
?>
<div class="card">
    <div class="card-header">
        <h4>Your Cart Items</h4>
    </div>
    <?php if (!empty($items)):?>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item['name'] ?></td>
                        <td>
                            <img src="<?php echo \common\models\Product::formatImage($item['image']) ?>"
                                 style="width:50px"
                                 alt="">
                        </td>
                        <td><?php echo Yii::$app->formatter->asCurrency($item['price']) ?></td>
                        <td><?php echo $item['quantity'] ?></td>
                        <td><?php echo Yii::$app->formatter->asCurrency($item['total_price']) ?></td>
                        <td><?php echo \yii\helpers\Html::a('Delete', ['/cart/delete', 'id' => $item['id']], [
                                'class' => 'btn btn-outline-danger btn-sm',
                                'data-method' => 'post',
                                'data-confirm'=>'Are you sure you want to remove this item from cart?'
                            ]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-right">
                <a href="<?php echo \yii\helpers\Url::to(['/cart/checkout']) ?>"
                   class="btn btn-primary btn-sm">Checkout</a>
            </div>
        </div>
    <?php else:?>
        <div class="text-center text-center p-5">
            There are no items in the cart
        </div>
    <?php endif;?>
</div>