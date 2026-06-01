<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Product */

?>

<div class="product-card">
    <?php if ($model->mainPhoto): ?>
        <img src="<?= Html::encode($model->mainPhoto->thumbSrc) ?>" alt="<?= Html::encode($model->name) ?>" class="product-card__img">
    <?php endif; ?>

    <div class="product-card__body">
        <h3 class="product-card__title">
            <?= Html::a(Html::encode($model->name), ['/shop/catalog/product', 'id' => $model->id]) ?>
        </h3>
        <div class="product-card__price">
            <?php if ($model->price_old): ?>
                <s class="text-muted"><?= PriceHelper::format($model->price_old) ?> ₽</s>
            <?php endif; ?>
            <strong><?= PriceHelper::format($model->price_new) ?> ₽</strong>
        </div>
        <?= Html::a('В корзину', ['/shop/cart/add', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary mt-2']) ?>
    </div>
</div>
