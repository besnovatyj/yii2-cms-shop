<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\forms\frontend\search\SearchForm;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
/* @var $searchForm SearchForm */

$this->title = 'Поиск товаров';
?>

<h1><?= $this->title ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'     => '_product_card',
    'layout'       => "{items}\n{pager}",
    'emptyText'    => 'По вашему запросу ничего не найдено.',
]) ?>
