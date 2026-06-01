<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\entities\order\Status;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Order */

$statusLabel = Status::getLabels()[$model->current_status] ?? 'Неизвестно';
?>

<div class="order-row d-flex justify-content-between align-items-center border-bottom py-2">
    <div>
        <strong><?= Html::a('Заказ #' . $model->id, ['/shop/order/view', 'id' => $model->id]) ?></strong>
        <br>
        <small class="text-muted"><?= date('d.m.Y H:i', $model->created_at) ?></small>
    </div>
    <div>
        <span class="badge bg-secondary"><?= Html::encode($statusLabel) ?></span>
    </div>
    <div>
        <strong><?= PriceHelper::format($model->cost) ?> ₽</strong>
    </div>
</div>
