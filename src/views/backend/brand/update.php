<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\forms\backend\BrandForm;
use yii\web\View;

/* @var $this View */
/* @var $model BrandForm */
/* @var $brand Brand */

$this->title                   = 'Редактировать: ' . $brand->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $brand->name, 'url' => ['view', 'id' => $brand->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_form', ['model' => $model]);
