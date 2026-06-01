<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Shop\forms\backend\search\DeliveryMethodSearch;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel DeliveryMethodSearch */

$this->title                   = 'Доставка';
$this->params['breadcrumbs'][] = $this->title;
?>

<p><?= Html::a('Создать способ доставки', ['create'], ['class' => 'btn btn-success']) ?></p>

<div class="card">
    <div class="card-header"><?= Html::encode($this->title) ?></div>
    <div class="card-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                'id',
                'name',
                'cost',
                'min_weight',
                [
                    'attribute' => 'max_weight',
                    'value'     => static fn($m) => $m->max_weight !== null ? (string) $m->max_weight : '∞',
                ],
                [
                    'attribute' => 'active',
                    'value'     => static fn($m) => $m->active ? 'Да' : 'Нет',
                    'filter'    => ['1' => 'Да', '0' => 'Нет'],
                ],
                'sort',
                ['class' => ActionColumn::class, 'template' => '{view} {update} {delete}'],
            ],
        ]) ?>
    </div>
    <div class="card-footer clearfix">
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>
