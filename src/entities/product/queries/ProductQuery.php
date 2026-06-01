<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product\queries;

use yii\db\ActiveQuery;

/**
 * Запросы для сущности Product.
 */
class ProductQuery extends ActiveQuery
{
    /**
     * Только опубликованные (активные) товары.
     */
    public function active(?string $alias = null): self
    {
        $field = $alias ? "{$alias}.status" : 'status';
        return $this->andWhere([$field => \Besnovatyj\Shop\entities\product\Product::STATUS_ACTIVE]);
    }
}
