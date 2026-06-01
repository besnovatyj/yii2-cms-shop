<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\Brand;
use RuntimeException;
use Throwable;

/**
 * Репозиторий брендов.
 */
class BrandRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): Brand
    {
        if (!$brand = Brand::findOne($id)) {
            throw new NotFoundException('Бренд не найден.');
        }
        return $brand;
    }

    /**
     * @throws RuntimeException
     */
    public function save(Brand $brand): void
    {
        if (!$brand->save()) {
            throw new RuntimeException('Ошибка сохранения бренда.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(Brand $brand): void
    {
        if (!$brand->delete()) {
            throw new RuntimeException('Ошибка удаления бренда.');
        }
    }
}
