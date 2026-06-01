<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\Tag;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $tag Tag */

$this->title                   = $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Редактировать', ['update', 'id' => $tag->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $tag->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Вы уверены?', 'method' => 'post'],
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $tag,
    'attributes' => ['id', 'name', 'slug'],
]) ?>
