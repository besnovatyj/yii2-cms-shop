<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\DeliveryMethod;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $method DeliveryMethod */

$this->title                   = $method->name;
$this->params['breadcrumbs'][] = ['label' => 'Доставка', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Редактировать', ['update', 'id' => $method->id], ['class' => 'btn btn-primary']) ?>
    <?php if ($method->active): ?>
        <?= Html::a('Деактивировать', ['deactivate', 'id' => $method->id], [
            'class' => 'btn btn-warning',
            'data'  => ['method' => 'post'],
        ]) ?>
    <?php else: ?>
        <?= Html::a('Активировать', ['activate', 'id' => $method->id], [
            'class' => 'btn btn-success',
            'data'  => ['method' => 'post'],
        ]) ?>
    <?php endif; ?>
    <?= Html::a('Удалить', ['delete', 'id' => $method->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Вы уверены?', 'method' => 'post'],
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $method,
    'attributes' => [
        'id',
        'name',
        [
            'attribute' => 'cost',
            'value'     => PriceHelper::format($method->cost) . ' ₽',
        ],
        'min_weight',
        [
            'attribute' => 'max_weight',
            'value'     => $method->max_weight !== null ? (string) $method->max_weight : '— без ограничения',
        ],
        [
            'attribute' => 'active',
            'value'     => $method->active ? 'Да' : 'Нет',
        ],
        'sort',
    ],
]) ?>
