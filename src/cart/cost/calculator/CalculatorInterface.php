<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\cost\calculator;

use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\cart\cost\Cost;

/**
 * Контракт калькулятора стоимости корзины.
 */
interface CalculatorInterface
{
    /**
     * Рассчитывает стоимость набора позиций корзины.
     *
     * @param CartItem[] $items
     */
    public function getCost(array $items): Cost;
}
