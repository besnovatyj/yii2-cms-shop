<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\order\Order;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $order Order */

$this->title                   = 'Заказ #' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Редактировать', ['update', 'id' => $order->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $order->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Удалить заказ?', 'method' => 'post'],
    ]) ?>
</p>

<div class="row">
    <div class="col-md-6">
        <?= DetailView::widget([
            'model'      => $order,
            'attributes' => [
                'id',
                'current_status',
                'cost',
                ['label' => 'Покупатель', 'value' => $order->customerData->name . ' / ' . $order->customerData->phone],
                ['label' => 'Доставка', 'value' => $order->deliveryData->address . ', ' . $order->deliveryData->index],
                'delivery_method_name',
                'delivery_cost',
                'note:ntext',
                ['attribute' => 'created_at', 'format' => 'datetime'],
            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Позиции заказа</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Товар</th><th>Цена</th><th>Кол-во</th></tr></thead>
                    <tbody>
                    <?php foreach ($order->items as $item): ?>
                        <tr>
                            <td><?= Html::encode($item->name) ?></td>
                            <td><?= number_format($item->price, 0, '.', ' ') ?></td>
                            <td><?= $item->quantity ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
