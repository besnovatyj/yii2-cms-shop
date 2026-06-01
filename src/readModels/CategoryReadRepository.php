<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\readModels;

use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\TreeManager\Manager\TreeQueryScope;

/**
 * Репозиторий чтения для категорий (read-side).
 */
class CategoryReadRepository
{
    private TreeQueryScope $treeScope;

    public function __construct()
    {
        $this->treeScope = new TreeQueryScope(Category::class);
    }

    /**
     * Возвращает дерево категорий (все корни + потомки).
     *
     * @return Category[]
     */
    public function getTree(): array
    {
        return Category::find()->orderBy(['lft' => SORT_ASC])->all();
    }

    /**
     * Возвращает категорию по slug.
     */
    public function findBySlug(string $slug): ?Category
    {
        return Category::findOne(['slug' => $slug]);
    }

    /**
     * Возвращает категорию по ID.
     */
    public function find(int $id): ?Category
    {
        return Category::findOne($id);
    }

    /**
     * Возвращает хлебные крошки для категории (путь от корня до текущей).
     *
     * @return Category[]
     */
    public function getPath(Category $category): array
    {
        return $this->treeScope->ancestors($category, andSelf: true);
    }
}
