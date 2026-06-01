<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Discount;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $discount Discount */

$this->title                   = $discount->name;
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Редактировать', ['update', 'id' => $discount->id], ['class' => 'btn btn-primary']) ?>
    <?php if ($discount->active): ?>
        <?= Html::a('Деактивировать', ['deactivate', 'id' => $discount->id], [
            'class' => 'btn btn-warning',
            'data'  => ['method' => 'post'],
        ]) ?>
    <?php else: ?>
        <?= Html::a('Активировать', ['activate', 'id' => $discount->id], [
            'class' => 'btn btn-success',
            'data'  => ['method' => 'post'],
        ]) ?>
    <?php endif; ?>
    <?= Html::a('Удалить', ['delete', 'id' => $discount->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Вы уверены?', 'method' => 'post'],
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $discount,
    'attributes' => [
        'id',
        'name',
        [
            'attribute' => 'percent',
            'value'     => $discount->percent . '%',
        ],
        'from_date',
        'to_date',
        [
            'attribute' => 'active',
            'value'     => $discount->active ? 'Да' : 'Нет',
        ],
        [
            'label' => 'Действует сейчас',
            'value' => $discount->isEnabled() ? 'Да' : 'Нет',
        ],
        'sort',
    ],
]) ?>
