<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use yii\db\ActiveRecord;

/**
 * Связь товара с похожими (сопутствующими) товарами.
 *
 * @property int $product_id
 * @property int $related_id
 */
class RelatedAssignment extends ActiveRecord
{
    /**
     * Создаёт запись привязки.
     */
    public static function create(int $productId, int $relatedId): self
    {
        $assignment             = new static();
        $assignment->product_id = $productId;
        $assignment->related_id = $relatedId;
        return $assignment;
    }

    /**
     * Проверяет, является ли связанный товар тем, что ищем.
     */
    public function isForProduct(int $id): bool
    {
        return $this->related_id === $id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_related_assignments}}';
    }
}
