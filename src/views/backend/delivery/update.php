<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\DeliveryMethod;
use yii\web\View;

/* @var $this View */
/* @var $model \Besnovatyj\Shop\forms\backend\DeliveryMethodForm */
/* @var $method DeliveryMethod */

$this->title                   = 'Редактировать: ' . $method->name;
$this->params['breadcrumbs'][] = ['label' => 'Доставка', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $method->name, 'url' => ['view', 'id' => $method->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_form', ['model' => $model]);
