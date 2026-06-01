<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\readModels;

use Besnovatyj\Shop\entities\Brand;
use yii\data\ActiveDataProvider;

/**
 * Репозиторий чтения для брендов (read-side).
 */
class BrandReadRepository
{
    /**
     * Список всех брендов с пагинацией.
     */
    public function getAll(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Brand::find()->orderBy(['sort' => SORT_ASC, 'name' => SORT_ASC]),
            'sort'  => false,
        ]);
    }

    /**
     * Возвращает все бренды в виде массива (для выпадающего списка).
     *
     * @return Brand[]
     */
    public function findAll(): array
    {
        return Brand::find()->orderBy(['sort' => SORT_ASC, 'name' => SORT_ASC])->all();
    }

    /**
     * Ищет бренд по slug.
     */
    public function findBySlug(string $slug): ?Brand
    {
        return Brand::findOne(['slug' => $slug]);
    }
}
