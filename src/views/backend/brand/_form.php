<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\forms\backend\BrandForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model BrandForm */
?>
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
<?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'sort')->textInput(['type' => 'number', 'min' => 0]) ?>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mt-2']) ?>
<?php ActiveForm::end(); ?>
