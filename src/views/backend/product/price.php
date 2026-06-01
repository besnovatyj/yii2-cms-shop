<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\forms\backend\product\PriceForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model PriceForm */
/* @var $product Product */

$this->title                   = 'Цена: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Цена';
?>

<div class="card" style="max-width:400px">
    <div class="card-header"><h5>Редактировать цену</h5></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'new')->textInput(['type' => 'number', 'min' => 0]) ?>
        <?= $form->field($model, 'old')->textInput(['type' => 'number', 'min' => 0]) ?>
        <div class="mt-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['view', 'id' => $product->id], ['class' => 'btn btn-secondary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
