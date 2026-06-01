<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Characteristic;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $characteristic Characteristic */

$this->title                   = $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Характеристики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Редактировать', ['update', 'id' => $characteristic->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $characteristic->id], [
        'class' => 'btn btn-danger',
        'data'  => ['confirm' => 'Вы уверены?', 'method' => 'post'],
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $characteristic,
    'attributes' => [
        'id',
        'name',
        'type',
        [
            'attribute' => 'required',
            'value'     => $characteristic->required ? 'Да' : 'Нет',
        ],
        'default',
        [
            'label' => 'Варианты',
            'value' => implode(', ', $characteristic->variants) ?: '—',
        ],
        'sort',
    ],
]) ?>
