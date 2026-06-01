<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Shop\forms\backend\search\CharacteristicSearch;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel CharacteristicSearch */

$this->title                   = 'Характеристики';
$this->params['breadcrumbs'][] = $this->title;
?>

<p><?= Html::a('Создать характеристику', ['create'], ['class' => 'btn btn-success']) ?></p>

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
                'type',
                [
                    'attribute' => 'required',
                    'value'     => fn($model) => $model->required ? 'Да' : 'Нет',
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
