<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Brand;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this View */
/* @var $brand Brand */
/* @var $dataProvider ActiveDataProvider */

$this->title = $brand->name;
?>

<h1><?= $this->title ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'     => '_product_card',
    'layout'       => "{items}\n{pager}",
    'emptyText'    => 'Товары не найдены.',
]) ?>
