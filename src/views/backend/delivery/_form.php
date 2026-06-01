<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\forms\backend\DeliveryMethodForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DeliveryMethodForm */

?>

<?php $form = ActiveForm::begin(); ?>

<div class="card">
    <div class="card-header"><h5>Способ доставки</h5></div>
    <div class="card-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'cost')->textInput(['type' => 'number', 'min' => 0]) ?>
        <?= $form->field($model, 'minWeight')->textInput(['type' => 'number', 'min' => 0]) ?>
        <?= $form->field($model, 'maxWeight')->textInput(['type' => 'number', 'min' => 0]) ?>
        <?= $form->field($model, 'sort')->textInput(['type' => 'number', 'min' => 0]) ?>
    </div>
    <div class="card-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
