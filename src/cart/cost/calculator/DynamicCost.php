<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\cost\calculator;

use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\cart\cost\Cost;
use Besnovatyj\Shop\cart\cost\Discount as CartDiscount;
use Besnovatyj\Shop\entities\Discount as DiscountEntity;

/**
 * Калькулятор с динамическими скидками.
 *
 * Использует паттерн Decorator: оборачивает SimpleCost,
 * затем применяет активные скидки из БД.
 */
class DynamicCost implements CalculatorInterface
{
    /**
     * @param CalculatorInterface $next Базовый калькулятор (обычно SimpleCost).
     */
    public function __construct(
        private readonly CalculatorInterface $next,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getCost(array $items): Cost
    {
        /** @var DiscountEntity[] $discounts */
        $discounts = DiscountEntity::find()->active()->orderBy(['sort' => SORT_ASC])->all();

        $cost = $this->next->getCost($items);

        foreach ($discounts as $discount) {
            if ($discount->isEnabled()) {
                $amount = $cost->getOrigin() * $discount->percent / 100;
                $cost   = $cost->withDiscount(new CartDiscount($amount, $discount->name));
            }
        }

        return $cost;
    }
}
