<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use yii\web\View;

/* @var $this View */
/* @var $model \Besnovatyj\Shop\forms\backend\product\ProductCreateForm */
/* @var $characteristics \Besnovatyj\Shop\entities\product\Characteristic[] */

$this->title                   = 'Создать товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_form', ['model' => $model, 'characteristics' => $characteristics]);
