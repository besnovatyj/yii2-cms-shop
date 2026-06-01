<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\listeners;

use Besnovatyj\Shop\entities\product\events\ProductAppearedInStock;

/**
 * Обрабатывает событие появления товара в наличии.
 *
 * Можно расширить: отправка email-уведомлений пользователям из вишлиста,
 * отправка push-уведомлений и т.д.
 */
class ProductAppearedInStockListener
{
    public function handle(ProductAppearedInStock $event): void
    {
        // TODO: уведомить пользователей из вишлиста о появлении товара
    }
}
