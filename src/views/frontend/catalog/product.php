<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\forms\frontend\AddToCartForm;
use Besnovatyj\Shop\forms\frontend\ReviewForm;
use Besnovatyj\Shop\helpers\PriceHelper;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $product Product */
/* @var $cartForm AddToCartForm */
/* @var $reviewForm ReviewForm */

$this->title = $product->name;
?>

<div class="product-page">
    <div class="row">
        <div class="col-md-5">
            <?php if ($product->photos): ?>
                <?php foreach ($product->photos as $photo): ?>
                    <img src="<?= Html::encode($photo->thumbSrc) ?>" alt="<?= Html::encode($product->name) ?>" class="img-fluid mb-2">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-7">
            <h1><?= Html::encode($product->name) ?></h1>

            <?php if ($product->brand): ?>
                <div class="mb-2">
                    Бренд: <?= Html::a(Html::encode($product->brand->name), ['/shop/catalog/brand', 'id' => $product->brand->id]) ?>
                </div>
            <?php endif; ?>

            <div class="product-price mb-3">
                <?php if ($product->price_old): ?>
                    <s class="text-muted fs-5"><?= PriceHelper::format($product->price_old) ?> ₽</s>
                <?php endif; ?>
                <span class="fs-3 fw-bold"><?= PriceHelper::format($product->price_new) ?> ₽</span>
            </div>

            <?php if ($product->quantity > 0): ?>
                <?php $form = ActiveForm::begin(['action' => ['/shop/cart/add', 'id' => $product->id]]); ?>
                <?php if ($product->modifications): ?>
                    <?= $form->field($cartForm, 'modification')->dropDownList(
                        array_combine(
                            array_column($product->modifications, 'id'),
                            array_column($product->modifications, 'name'),
                        )
                    ) ?>
                <?php endif; ?>
                <?= $form->field($cartForm, 'quantity')->textInput(['type' => 'number', 'min' => 1, 'max' => $product->quantity]) ?>
                <?= Html::submitButton('В корзину', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            <?php else: ?>
                <p class="text-danger">Нет в наличии</p>
            <?php endif; ?>

            <?php if ($product->description): ?>
                <div class="product-description mt-4">
                    <h4>Описание</h4>
                    <?= $product->description ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($product->values): ?>
        <div class="product-characteristics mt-4">
            <h4>Характеристики</h4>
            <table class="table table-bordered table-sm">
                <?php foreach ($product->values as $value): ?>
                    <tr>
                        <td><?= Html::encode($value->characteristic->name ?? '') ?></td>
                        <td><?= Html::encode($value->value) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <!-- Отзывы -->
    <div class="product-reviews mt-4" id="reviews">
        <h4>Отзывы</h4>
        <?php foreach ($product->reviews as $review): ?>
            <?php if ($review->isActive()): ?>
                <div class="review mb-3 p-3 border rounded">
                    <div class="review__rating">Оценка: <?= $review->vote ?>/5</div>
                    <div class="review__text"><?= Html::encode($review->text) ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (!Yii::$app->user->isGuest): ?>
            <h5 class="mt-3">Написать отзыв</h5>
            <?php $rform = ActiveForm::begin(); ?>
            <?= $rform->field($reviewForm, 'vote')->dropDownList([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']) ?>
            <?= $rform->field($reviewForm, 'text')->textarea(['rows' => 4]) ?>
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-outline-primary']) ?>
            <?php ActiveForm::end(); ?>
        <?php else: ?>
            <p><a href="/site/login">Войдите</a>, чтобы оставить отзыв.</p>
        <?php endif; ?>
    </div>
</div>
