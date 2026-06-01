<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\entities\order\Status;
use Besnovatyj\Shop\helpers\PriceHelper;
use Yii;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $order Order */

$statusLabel = Status::getLabels()[$order->current_status] ?? 'Неизвестно';
$this->title = 'Заказ #' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
<?php endif; ?>

<?= DetailView::widget([
    'model'      => $order,
    'attributes' => [
        'id',
        [
            'label' => 'Дата',
            'value' => date('d.m.Y H:i', $order->created_at),
        ],
        [
            'label' => 'Статус',
            'value' => $statusLabel,
        ],
        [
            'label' => 'Покупатель',
            'value' => $order->customerData->name,
        ],
        [
            'label' => 'Телефон',
            'value' => $order->customerData->phone,
        ],
        [
            'label' => 'Способ доставки',
            'value' => $order->delivery_method_name,
        ],
        [
            'label' => 'Адрес доставки',
            'value' => $order->deliveryData->address,
        ],
        'note',
        [
            'attribute' => 'cost',
            'value'     => PriceHelper::format($order->cost) . ' ₽',
        ],
    ],
]) ?>

<h4 class="mt-4">Состав заказа</h4>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Товар</th>
        <th>Цена</th>
        <th>Количество</th>
        <th>Сумма</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($order->items as $item): ?>
        <tr>
            <td><?= Html::encode($item->product_name) ?></td>
            <td><?= PriceHelper::format($item->price) ?> ₽</td>
            <td><?= $item->quantity ?></td>
            <td><?= PriceHelper::format($item->price * $item->quantity) ?> ₽</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3" class="text-end fw-bold">Итого:</td>
        <td><?= PriceHelper::format($order->cost) ?> ₽</td>
    </tr>
    </tfoot>
</table>
