<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\forms\frontend\AddToCartForm;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $product Product */
/* @var $model AddToCartForm */

$this->title = 'Добавить в корзину: ' . $product->name;
?>

<h2><?= Html::encode($product->name) ?></h2>
<p class="fs-5 fw-bold"><?= PriceHelper::format($product->price_new) ?> ₽</p>

<?php $form = ActiveForm::begin(); ?>

<?php if ($product->modifications): ?>
    <?= $form->field($model, 'modification')->dropDownList(
        array_combine(
            array_column($product->modifications, 'id'),
            array_column($product->modifications, 'name'),
        )
    ) ?>
<?php endif; ?>

<?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'min' => 1, 'max' => $product->quantity]) ?>

<div class="d-flex gap-2">
    <?= Html::submitButton('Добавить в корзину', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Назад', ['product', 'id' => $product->id], ['class' => 'btn btn-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>
