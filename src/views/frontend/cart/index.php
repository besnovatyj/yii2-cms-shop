<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\helpers\PriceHelper;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $cart Cart */

$this->title = 'Корзина';
?>

<h1><?= $this->title ?></h1>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Html::encode(Yii::$app->session->getFlash('error')) ?></div>
<?php endif; ?>

<form id="cart-form"
      hx-post="<?= Url::to(['/shop/cart/quantity']) ?>"
      hx-target="#cart-items"
      hx-swap="outerHTML">
    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

    <?= $this->render('_items', ['cart' => $cart]) ?>

    <?php if ($cart->getItems()): ?>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-outline-secondary">Обновить</button>
            <?= Html::a('Оформить заказ', ['/shop/checkout/index'], ['class' => 'btn btn-success']) ?>
        </div>

        <?php $discounts = $cart->getCost()->getDiscounts(); ?>
        <?php if ($discounts): ?>
            <div class="mt-3">
                <h6>Скидки:</h6>
                <?php foreach ($discounts as $discount): ?>
                    <div><?= Html::encode($discount->getName()) ?>: −<?= PriceHelper::format((int)$discount->getValue()) ?> ₽</div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</form>
