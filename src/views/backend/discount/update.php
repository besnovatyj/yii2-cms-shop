<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Discount;
use yii\web\View;

/* @var $this View */
/* @var $model \Besnovatyj\Shop\forms\backend\DiscountForm */
/* @var $discount Discount */

$this->title                   = 'Редактировать: ' . $discount->name;
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $discount->name, 'url' => ['view', 'id' => $discount->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_form', ['model' => $model]);
