<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\cost;

/**
 * Стоимость корзины с учётом скидок (иммутабельный Value Object).
 */
final class Cost
{
    /** @var Discount[] */
    private array $discounts;

    /**
     * @param float      $value     Исходная стоимость (без скидок).
     * @param Discount[] $discounts Применённые скидки.
     */
    public function __construct(
        private readonly float $value,
        array $discounts = [],
    ) {
        $this->discounts = $discounts;
    }

    /**
     * Возвращает новый объект с добавленной скидкой.
     */
    public function withDiscount(Discount $discount): self
    {
        return new self($this->value, [...$this->discounts, $discount]);
    }

    /**
     * Возвращает исходную стоимость (до скидок).
     */
    public function getOrigin(): float
    {
        return $this->value;
    }

    /**
     * Возвращает итоговую стоимость (после вычета всех скидок).
     */
    public function getTotal(): float
    {
        return max(0.0, $this->value - array_sum(
            array_map(fn(Discount $d) => $d->getValue(), $this->discounts)
        ));
    }

    /**
     * Возвращает список применённых скидок.
     *
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }
}
