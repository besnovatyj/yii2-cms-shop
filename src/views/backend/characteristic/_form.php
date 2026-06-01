<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Characteristic;
use Besnovatyj\Shop\forms\backend\CharacteristicForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model CharacteristicForm */

$typeOptions = [
    Characteristic::TYPE_STRING  => 'Строка',
    Characteristic::TYPE_INTEGER => 'Целое число',
    Characteristic::TYPE_FLOAT   => 'Вещественное число',
];

?>

<?php $form = ActiveForm::begin(); ?>

<div class="card">
    <div class="card-header"><h5>Основное</h5></div>
    <div class="card-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'type')->dropDownList($typeOptions) ?>
        <?= $form->field($model, 'required')->checkbox() ?>
        <?= $form->field($model, 'default')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'sort')->textInput(['type' => 'number', 'min' => 0]) ?>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><h5>Список допустимых значений (select)</h5></div>
    <div class="card-body">
        <div id="variants-list">
            <?php foreach ($model->variants as $i => $variant): ?>
                <div class="input-group mb-2">
                    <?= Html::textInput("CharacteristicForm[variants][{$i}]", $variant, ['class' => 'form-control']) ?>
                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()">−</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm mt-1" id="add-variant">+ Добавить значение</button>
    </div>
    <div class="card-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
document.getElementById('add-variant').addEventListener('click', function () {
    var list  = document.getElementById('variants-list');
    var index = list.querySelectorAll('.input-group').length;
    var div   = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = '<input type="text" name="CharacteristicForm[variants][' + index + ']" class="form-control">'
        + '<button type="button" class="btn btn-outline-danger" onclick="this.closest(\'.input-group\').remove()">−</button>';
    list.appendChild(div);
});
</script>
