<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product\events;

use Besnovatyj\Shop\entities\product\Product;

/**
 * Событие: товар появился в наличии (переход с 0 на положительный остаток).
 */
class ProductAppearedInStock
{
    /**
     * @param Product $product Товар, появившийся в наличии.
     */
    public function __construct(
        public readonly Product $product,
    ) {}
}
