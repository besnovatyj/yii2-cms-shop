<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\cost;

/**
 * Скидка в корзине (Value Object).
 */
final class Discount
{
    /**
     * @param float  $value Сумма скидки.
     * @param string $name  Название скидки.
     */
    public function __construct(
        private readonly float  $value,
        private readonly string $name,
    ) {}

    /**
     * Возвращает сумму скидки.
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Возвращает название скидки.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
