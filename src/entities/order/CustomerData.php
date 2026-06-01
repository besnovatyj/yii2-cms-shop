<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\order;

/**
 * Данные покупателя.
 */
final readonly class CustomerData
{
    public function __construct(
        public string $phone,
        public string $name,
    ) {}
}
