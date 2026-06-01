<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\Discount;
use RuntimeException;
use Throwable;

/**
 * Репозиторий скидок.
 */
class DiscountRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): Discount
    {
        if (!$discount = Discount::findOne($id)) {
            throw new NotFoundException('Скидка не найдена.');
        }
        return $discount;
    }

    /**
     * @throws RuntimeException
     */
    public function save(Discount $discount): void
    {
        if (!$discount->save()) {
            throw new RuntimeException('Ошибка сохранения скидки.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(Discount $discount): void
    {
        if (!$discount->delete()) {
            throw new RuntimeException('Ошибка удаления скидки.');
        }
    }
}
