<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title                   = 'Скидки';
$this->params['breadcrumbs'][] = $this->title;
?>

<p><?= Html::a('Создать скидку', ['create'], ['class' => 'btn btn-success']) ?></p>

<div class="card">
    <div class="card-header"><?= Html::encode($this->title) ?></div>
    <div class="card-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                'id',
                'name',
                [
                    'attribute' => 'percent',
                    'value'     => fn($model) => $model->percent . '%',
                ],
                'from_date',
                'to_date',
                [
                    'attribute' => 'active',
                    'value'     => fn($model) => $model->active ? 'Да' : 'Нет',
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
