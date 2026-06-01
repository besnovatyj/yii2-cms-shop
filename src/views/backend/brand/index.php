<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\forms\backend\search\BrandSearch;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\data\ActiveDataProvider;
use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel BrandSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title                   = 'Бренды';
$this->params['breadcrumbs'][] = $this->title;
?>

<p><?= Html::a('Создать бренд', ['create'], ['class' => 'btn btn-success']) ?></p>

<div class="card">
    <div class="card-header"><?= Html::encode($this->title) ?></div>
    <div class="card-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                'id',
                ['attribute' => 'name', 'value' => static fn(Brand $m) => Html::a(Html::encode($m->name), ['view', 'id' => $m->id]), 'format' => 'raw'],
                'slug',
                'sort',
                ['class' => ActionColumn::class, 'template' => '{view} {update} {delete}'],
            ],
        ]) ?>
    </div>
    <div class="card-footer clearfix">
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>
