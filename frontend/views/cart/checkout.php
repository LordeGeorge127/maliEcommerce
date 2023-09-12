<?php
/**
 * @var $order Order
 * @var  OrderAddress $orderAddress
 * @var array $cartItems
 * @var $totalPrice float
 * @var $productQuantity int
 */

use common\models\Order;
use common\models\OrderAddress;
use yii\bootstrap5\ActiveForm;

$USD = 0.0068;
$totalPriceUSD = $totalPrice * $USD;
?>
<?php $form = ActiveForm::begin([
    'id' => 'checkout-form',
]) ?>
<div class="row">
    <div class="col">

        <div class="card m-3">
            <div class="card-header">
                Account Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($order, 'firstname')->textInput(['autofocus' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($order, 'lastname')->textInput(['autofocus' => true]) ?>
                    </div>
                </div>
                <?= $form->field($order, 'email') ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Address Information
            </div>
            <div class="card-body">
                <?= $form->field($orderAddress, 'address')->textInput(['autofocus' => true]) ?>
                <?= $form->field($orderAddress, 'city')->textInput(['autofocus' => true]) ?>
                <?= $form->field($orderAddress, 'state')->textInput(['autofocus' => true]) ?>
                <?= $form->field($orderAddress, 'country')->textInput(['autofocus' => true]) ?>
                <?= $form->field($orderAddress, 'zipcode')->textInput(['autofocus' => true]) ?>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4>Order Summary</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td> Total Items</td>
                        <td><?php echo $productQuantity ?></td>
                    </tr>
                    <tr>
                        <td>Total Price</td>
                        <td><?php echo Yii::$app->formatter->asCurrency($totalPrice) ?></td>
                    </tr>
                    <tr>
                        <td>Total Price in USD</td>
                        <td><?php echo Yii::$app->formatter->asCurrency($totalPriceUSD, 'USD') ?></td>
                    </tr>
                </table>
            </div>
            <!--            <div id="paypal-button-container"></div>-->
            <p class="text-right p-2">
                <button class="btn btn-secondary">Checkout</button>
            </p>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
