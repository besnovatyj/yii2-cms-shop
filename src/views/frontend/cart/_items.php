<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\helpers\PriceHelper;
use Yii;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $cart Cart */

?>

<?php if (!$cart->getItems()): ?>
    <p class="text-muted">Корзина пуста.</p>
<?php else: ?>
    <table class="table table-bordered" id="cart-items">
        <thead>
        <tr>
            <th>Товар</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart->getItems() as $item): ?>
            <tr>
                <td>
                    <?= Html::a(Html::encode($item->getProduct()->name), ['/shop/catalog/product', 'id' => $item->getProductId()]) ?>
                    <?php if ($mod = $item->getModification()): ?>
                        <br><small class="text-muted"><?= Html::encode($mod->name) ?></small>
                    <?php endif; ?>
                </td>
                <td><?= PriceHelper::format($item->getPrice()) ?> ₽</td>
                <td>
                    <input type="number"
                           name="quantity_data[<?= $item->getId() ?>]"
                           value="<?= $item->getQuantity() ?>"
                           min="1"
                           class="form-control form-control-sm"
                           style="width:80px">
                </td>
                <td><?= PriceHelper::format($item->getCost()) ?> ₽</td>
                <td>
                    <?= Html::a('×', ['/shop/cart/remove', 'id' => $item->getProductId(), 'modId' => $item->getModificationId()], [
                        'class'           => 'btn btn-sm btn-outline-danger',
                        'hx-post'         => \yii\helpers\Url::to(['/shop/cart/remove', 'id' => $item->getProductId(), 'modId' => $item->getModificationId()]),
                        'hx-target'       => '#cart-items',
                        'hx-swap'         => 'outerHTML',
                        'data-csrf'       => Yii::$app->request->csrfToken,
                        'hx-headers'      => json_encode(['X-CSRF-Token' => Yii::$app->request->csrfToken]),
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="text-end fw-bold">Итого:</td>
            <td colspan="2"><?= PriceHelper::format($cart->getCost()->getTotal()) ?> ₽</td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>
