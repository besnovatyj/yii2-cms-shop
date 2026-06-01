<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\forms\frontend\order\OrderForm;
use Besnovatyj\Shop\helpers\PriceHelper;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $cart Cart */
/* @var $model OrderForm */

$this->title = 'Оформление заказа';
?>

<h1><?= $this->title ?></h1>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Html::encode(Yii::$app->session->getFlash('error')) ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-7">
        <?php $form = ActiveForm::begin(); ?>

        <div class="card mb-3">
            <div class="card-header"><h5>Покупатель</h5></div>
            <div class="card-body">
                <?= $form->field($model->customer, 'name')->textInput() ?>
                <?= $form->field($model->customer, 'phone')->textInput() ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Доставка</h5></div>
            <div class="card-body">
                <?= $form->field($model->delivery, 'method')->dropDownList($model->delivery->deliveryMethodsList()) ?>
                <?= $form->field($model->delivery, 'index')->textInput() ?>
                <?= $form->field($model->delivery, 'address')->textInput() ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Дополнительно</h5></div>
            <div class="card-body">
                <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
            </div>
        </div>

        <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-success btn-lg']) ?>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h5>Ваш заказ</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <?php foreach ($cart->getItems() as $item): ?>
                        <tr>
                            <td><?= Html::encode($item->getProduct()->name) ?></td>
                            <td class="text-end">
                                <?= $item->getQuantity() ?> × <?= PriceHelper::format($item->getPrice()) ?> ₽
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Итого:</span>
                    <span><?= PriceHelper::format((int)$cart->getCost()->getTotal()) ?> ₽</span>
                </div>
            </div>
        </div>
    </div>
</div>
