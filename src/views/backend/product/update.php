<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use yii\web\View;

/* @var $this View */
/* @var $model \Besnovatyj\Shop\forms\backend\product\ProductEditForm */
/* @var $product Product */
/* @var $characteristics \Besnovatyj\Shop\entities\product\Characteristic[] */

$this->title                   = 'Редактировать: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_form', [
    'model'           => $model,
    'product'         => $product,
    'characteristics' => $characteristics,
]);
