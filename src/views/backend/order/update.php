<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\forms\backend\order\OrderEditForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model OrderEditForm */
/* @var $order Order */

$this->title                   = 'Заказ #' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $order->id, 'url' => ['view', 'id' => $order->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Покупатель</h5></div>
            <div class="card-body">
                <?= $form->field($model->customer, 'name')->textInput() ?>
                <?= $form->field($model->customer, 'phone')->textInput() ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Доставка</h5></div>
            <div class="card-body">
                <?= $form->field($model->delivery, 'method')->dropDownList($model->delivery->deliveryMethodsList(), ['prompt' => 'Без доставки']) ?>
                <?= $form->field($model->delivery, 'index')->textInput() ?>
                <?= $form->field($model->delivery, 'address')->textarea(['rows' => 2]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mt-2">
            <div class="card-body">
                <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Отмена', ['view', 'id' => $order->id], ['class' => 'btn btn-secondary ms-1']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
