<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\cost\calculator;

use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\cart\cost\Cost;

/**
 * Простой калькулятор: суммирует стоимость позиций без скидок.
 */
class SimpleCost implements CalculatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCost(array $items): Cost
    {
        $total = array_sum(array_map(fn(CartItem $item) => $item->getCost(), $items));
        return new Cost((float) $total);
    }
}
