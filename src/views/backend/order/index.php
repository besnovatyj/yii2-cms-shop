<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Shop\entities\order\Status;
use Besnovatyj\Shop\forms\backend\search\OrderSearch;
use Besnovatyj\Shop\helpers\PriceHelper;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel OrderSearch */

$this->title                   = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header"><?= Html::encode($this->title) ?></div>
    <div class="card-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                'id',
                [
                    'attribute' => 'created_at',
                    'value'     => fn($model) => date('d.m.Y H:i', $model->created_at),
                    'filter'    => false,
                ],
                [
                    'label' => 'Покупатель',
                    'value' => fn($model) => $model->customerData->name,
                ],
                [
                    'attribute' => 'current_status',
                    'value'     => fn($model) => Status::getLabels()[$model->current_status] ?? '—',
                    'filter'    => $searchModel->statusList(),
                ],
                [
                    'attribute' => 'cost',
                    'value'     => fn($model) => PriceHelper::format($model->cost) . ' ₽',
                    'filter'    => false,
                ],
                ['class' => ActionColumn::class, 'template' => '{view} {update} {delete}'],
            ],
        ]) ?>
    </div>
    <div class="card-footer clearfix">
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>
