<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Characteristic;
use yii\web\View;

/* @var $this View */
/* @var $model \Besnovatyj\Shop\forms\backend\CharacteristicForm */
/* @var $characteristic Characteristic */

$this->title                   = 'Редактировать: ' . $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Характеристики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $characteristic->name, 'url' => ['view', 'id' => $characteristic->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_form', ['model' => $model]);
