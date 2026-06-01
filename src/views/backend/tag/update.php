<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Tag;
use yii\web\View;

/* @var $this View */
/* @var $model \Besnovatyj\Shop\forms\backend\TagForm */
/* @var $tag Tag */

$this->title                   = 'Редактировать: ' . $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tag->name, 'url' => ['view', 'id' => $tag->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

echo $this->render('_form', ['model' => $model]);
