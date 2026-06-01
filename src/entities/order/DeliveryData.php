<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\order;

/**
 * Данные доставки.
 */
final readonly class DeliveryData
{
    public function __construct(
        public ?string $index,
        public string  $address,
    ) {}
}
