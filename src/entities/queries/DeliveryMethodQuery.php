<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\queries;

use yii\db\ActiveQuery;

/**
 * Запросы для сущности DeliveryMethod.
 */
class DeliveryMethodQuery extends ActiveQuery
{
    /**
     * Только активные методы доставки.
     */
    public function active(?string $alias = null): self
    {
        $field = $alias ? "{$alias}.active" : 'active';
        return $this->andWhere([$field => true]);
    }
}
