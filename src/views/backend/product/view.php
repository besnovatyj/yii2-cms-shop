<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Images\widgets\upload\Widget;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $product Product */
/* @var $modificationsProvider ActiveDataProvider */
/* @var $reviewsProvider ActiveDataProvider */

$this->title                   = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?php if ($product->isActive()): ?>
        <?= Html::a('В черновик', ['draft', 'id' => $product->id], ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
    <?php else: ?>
        <?= Html::a('Активировать', ['activate', 'id' => $product->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
    <?php endif; ?>
    <?= Html::a('Редактировать', ['update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Цена', ['price', 'id' => $product->id], ['class' => 'btn btn-info']) ?>
    <?= Html::a('Количество', ['quantity', 'id' => $product->id], ['class' => 'btn btn-secondary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $product->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Вы уверены?', 'method' => 'post'],
    ]) ?>
</p>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5>Основное</h5></div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model'      => $product,
                        'attributes' => [
                            'id',
                            [
                                'label'  => 'Статус',
                                'value'  => $product->isActive()
                                    ? Html::tag('span', 'Активен', ['class' => 'badge bg-success'])
                                    : Html::tag('span', 'Черновик', ['class' => 'badge bg-secondary']),
                                'format' => 'raw',
                            ],
                            'code',
                            'name',
                            ['attribute' => 'brand_id', 'value' => $product->brand->name ?? '—'],
                            'weight',
                            'quantity',
                            'price_new',
                            'price_old',
                            ['attribute' => 'rating', 'value' => $product->rating ?? '—'],
                            ['label' => 'Категории', 'value' => implode(', ', \yii\helpers\ArrayHelper::getColumn($product->categories, 'name'))],
                            ['label' => 'Теги', 'value' => implode(', ', \yii\helpers\ArrayHelper::getColumn($product->tags, 'name'))],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5>SEO</h5></div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model'      => $product,
                        'attributes' => [
                            ['label' => 'Title', 'value' => $product->meta->title],
                            ['label' => 'Description', 'value' => $product->meta->description],
                            ['label' => 'Keywords', 'value' => $product->meta->keywords],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Фото -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" id="photos">
                <div class="card-header"><h5>Фотографии</h5></div>
                <div class="card-body">
                    <?= Widget::widget([
                        'ownerId'   => $product->id,
                        'endpoints' => [
                            'getImages'    => Url::to(['/Shop/backend/product/get-images'], true),
                            'setNewSort'   => Url::to(['/Shop/backend/product/set-new-sort'], true),
                            'upload'       => Url::to(['/Shop/backend/product/add-image'], true),
                            'deleteImage'  => Url::to(['/Shop/backend/product/delete-image'], true),
                            'setMainImage' => '/Shop/backend/product/set-main-image',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Модификации -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" id="modifications">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Модификации</h5>
                    <?= Html::a('Добавить', ['add-modification', 'id' => $product->id], ['class' => 'btn btn-sm btn-success']) ?>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $modificationsProvider,
                        'columns'      => [
                            'code',
                            'name',
                            'price',
                            'quantity',
                            [
                                'class'    => ActionColumn::class,
                                'template' => '{update} {delete}',
                                'urlCreator' => static fn($action, $m) => match ($action) {
                                    'update' => Url::to(['update-modification', 'id' => $product->id, 'modification_id' => $m->id]),
                                    'delete' => Url::to(['delete-modification', 'id' => $product->id, 'modification_id' => $m->id]),
                                },
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Отзывы -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" id="reviews">
                <div class="card-header"><h5>Отзывы</h5></div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $reviewsProvider,
                        'columns'      => [
                            'id',
                            'vote',
                            'text:ntext',
                            [
                                'label'  => 'Статус',
                                'value'  => static fn($m) => $m->isActive()
                                    ? Html::tag('span', 'Активен', ['class' => 'badge bg-success'])
                                    : Html::tag('span', 'Черновик', ['class' => 'badge bg-secondary']),
                                'format' => 'raw',
                            ],
                            [
                                'class'    => ActionColumn::class,
                                'template' => '{activate} {draft} {delete}',
                                'buttons'  => [
                                    'activate' => static fn($url, $m) => Html::a('✓', [
                                        'activate-review',
                                        'id'        => $product->id,
                                        'review_id' => $m->id,
                                    ], ['class' => 'text-success', 'data-method' => 'post']),
                                    'draft' => static fn($url, $m) => Html::a('✗', [
                                        'draft-review',
                                        'id'        => $product->id,
                                        'review_id' => $m->id,
                                    ], ['class' => 'text-warning', 'data-method' => 'post']),
                                    'delete' => static fn($url, $m) => Html::a('✕', [
                                        'delete-review',
                                        'id'        => $product->id,
                                        'review_id' => $m->id,
                                    ], ['class' => 'text-danger', 'data' => ['confirm' => 'Удалить?', 'method' => 'post']]),
                                ],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
