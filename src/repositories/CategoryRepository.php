<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\category\Category;
use RuntimeException;
use Throwable;

/**
 * Репозиторий категорий.
 */
class CategoryRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Категория не найдена.');
        }
        return $category;
    }

    /**
     * @throws RuntimeException
     */
    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new RuntimeException('Ошибка сохранения категории.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new RuntimeException('Ошибка удаления категории.');
        }
    }
}
