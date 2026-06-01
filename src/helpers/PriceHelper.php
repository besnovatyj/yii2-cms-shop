<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\helpers;

/**
 * Хелпер форматирования цены.
 */
class PriceHelper
{
    /**
     * Форматирует целое число как цену (разделитель тысяч — пробел).
     */
    public static function format(int $price): string
    {
        return number_format($price, 0, '.', ' ');
    }
}
