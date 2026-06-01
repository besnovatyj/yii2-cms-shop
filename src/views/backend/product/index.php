<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\forms\backend\search\ProductSearch;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title                = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class="card">
    <div class="card-header"><?= Html::encode($this->title) ?></div>
    <div class="card-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{summary}\n{items}",
            'columns'      => [
                [
                    'value' => static function (Product $model): string {
                        return $model->mainPhoto
                            ? Html::img($model->mainPhoto->getThumbUrl('file', 'admin'), ['style' => 'max-height:60px'])
                            : '';
                    },
                    'format'         => 'raw',
                    'contentOptions' => ['style' => 'width:70px'],
                ],
                'id',
                [
                    'attribute' => 'name',
                    'value'     => static fn(Product $m) => Html::a(Html::encode($m->name), ['view', 'id' => $m->id]),
                    'format'    => 'raw',
                ],
                'code',
                [
                    'attribute' => 'brand_id',
                    'value'     => 'brand.name',
                ],
                [
                    'attribute' => 'price_new',
                    'value'     => static fn(Product $m) => number_format($m->price_new, 0, '.', ' '),
                ],
                'quantity',
                [
                    'attribute' => 'status',
                    'filter'    => $searchModel->statusList(),
                    'value'     => static fn(Product $m) => $m->isActive()
                        ? Html::tag('span', 'Активен', ['class' => 'badge bg-success'])
                        : Html::tag('span', 'Черновик', ['class' => 'badge bg-secondary']),
                    'format'    => 'raw',
                ],
            ],
        ]) ?>
    </div>
    <div class="card-footer clearfix">
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>
