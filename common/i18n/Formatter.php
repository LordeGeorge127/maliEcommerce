<?php
namespace common\i18n;
use common\models\Order;

class Formatter extends \yii\i18n\Formatter
{
    public function asOrderStatus($status)
    {
        if ($status == Order::STATUS_COMPLETED) {
            return \yii\bootstrap5\Html::tag('span', 'Paid', ['class' => 'badge badge-success']);
        } elseif ($status == Order::STATUS_DRAFT) {
            return \yii\bootstrap5\Html::tag('span', 'Unpaid', ['class' => 'badge badge-secondary']);
        } else {
            return \yii\bootstrap5\Html::tag('span', 'Failed', ['class' => 'badge badge-danger']);

        }
    }
}