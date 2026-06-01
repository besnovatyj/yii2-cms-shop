<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\forms\backend\product\ModificationForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ModificationForm */
/* @var $product Product */

$isNew = $model->isNewRecord ?? true;
$this->title = $isNew
    ? 'Добавить модификацию: ' . $product->name
    : 'Редактировать модификацию: ' . $product->name;

$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="card">
    <div class="card-header"><h5>Модификация</h5></div>
    <div class="card-body">
        <?= $form->field($model, 'code')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'price')->textInput(['type' => 'number', 'min' => 0]) ?>
        <?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'min' => 0]) ?>
    </div>
    <div class="card-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['view', 'id' => $product->id], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
