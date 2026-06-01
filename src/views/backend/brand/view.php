<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Brand;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $brand Brand */

$this->title                   = $brand->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Редактировать', ['update', 'id' => $brand->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $brand->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Вы уверены?', 'method' => 'post'],
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $brand,
    'attributes' => [
        'id',
        'name',
        'slug',
        'description:ntext',
        'logo',
        'sort',
    ],
]) ?>
