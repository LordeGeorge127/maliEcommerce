<?php
/**
 * @var \common\models\Order $order
 */

use yii\helpers\Url;
$USD = 0.0068;
$totalPriceUSD = $order->total_price * $USD;
$orderAddress = $order->orderAddress;
?>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo Yii::$app->params['paypalClientId']?>&currency=USD"></script>
<h4>Order Summary:# <?php echo $order->id ?></h4>
<hr>
<div class="row">
    <div class="col">
        <h5>Account Information</h5>
        <table class="table">
            <tr>
                <td>Firstname</td>
                <td class="text-right"><?php echo $order->firstname ?></td>
            </tr>
            <tr>
                <td>Lastname</td>
                <td class="text-right"><?php echo $order->lastname ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td class="text-right">v</td>
            </tr>
        </table>
        <h5>Address Information</h5>
        <table class="table">
            <tr>
                <td>Address</td>
                <td><?php echo $orderAddress->address ?></td>
            </tr>
            <tr>
                <td>City</td>
                <td><?php echo $orderAddress->city ?></td>
            </tr>
            <tr>
                <td>State</td>
                <td><?php echo $orderAddress->state ?></td>
            </tr>
            <tr>
                <td>Country</td>
                <td><?php echo $orderAddress->country ?></td>
            </tr>
            <tr>
                <td>Zipcode</td>
                <td><?php echo $orderAddress->zipcode ?></td>
            </tr>


        </table>
    </div>
    <div class="col">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
            </thead>
            <?php foreach ($order->orderItems as $item): ?>
            <tbody>
            <tr>
                <td><img src="<?php echo $item->product->getImageUrl() ?>" style="width: 50px;"></td>
                <td><?php echo $item->product_name ?></td>
                <td><?php echo $item->quantity ?></td>
                <td><?php echo Yii::$app->formatter->asCurrency($item->quantity * $item->unit_price) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
        <table class="table">
            <tr>
                <td>Total Items</td>
                <td><?php echo $order->getItemsQuantity() ?></td>
            </tr>
            <tr>
                <td>Total Price</td>
                <td><?php echo Yii::$app->formatter->asCurrency($order->total_price) ?></td>
            </tr>
            <tr>
                <td>Total Price in USD</td>
                <td><?php echo Yii::$app->formatter->asCurrency($totalPriceUSD, 'USD') ?></td>
            </tr>

        </table>
        <div id="paypal-button-container"></div>
    </div>
</div>
<script>
    paypal.Buttons({
        createOrder: async (data, actions) => {
            try {
                return actions.order.create({
                    purchase_units: [
                        {
                            amount: {
                                value:  <?php echo $totalPriceUSD ?>
                            }
                        }
                    ]
                })
            } catch (error) {
                console.error(error);
                // Handle the error or display an appropriate error message to the user
            }
        },
        onApprove: function (data, actions) {
            console.log(data);
            console.log("actions below");
            console.log(actions);
            return actions.order.capture().then(function (details) {
                const $form = $('#checkout-form');
                const formData = $form.serializeArray();
                console.log("details below");
                console.log(details);
                formData.push({
                    name: 'transactionId',
                    value: details.id
                })
                formData.push({
                    name: 'orderId',
                    value: data.orderID
                })
                formData.push({
                    name: 'status',
                    value: details.status
                });

                $.ajax({
                    method: 'post',
                    url: '<?php echo Url::to(['/cart/submit-payment', 'orderId' => $order->id])?>',
                    data: formData,
                    success: function (res) {
                        console.log(res);
                        alert('Thanks for shopping with us');
                        $
                        window.location.href = '';
                        //todo send email to admin when order is made
                    }
                })

            });
        }


    }).render("#paypal-button-container");

</script>

