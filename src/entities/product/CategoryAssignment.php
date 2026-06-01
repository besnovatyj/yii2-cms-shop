<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use Besnovatyj\Shop\entities\category\Category;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Связь товара с дополнительными категориями.
 *
 * @property int $product_id
 * @property int $category_id
 *
 * @property Category $category
 */
class CategoryAssignment extends ActiveRecord
{
    /**
     * Создаёт запись привязки.
     */
    public static function create(int $productId, int $categoryId): self
    {
        $assignment              = new static();
        $assignment->product_id  = $productId;
        $assignment->category_id = $categoryId;
        return $assignment;
    }

    /**
     * Проверяет, относится ли привязка к указанной категории.
     */
    public function isForCategory(int $id): bool
    {
        return $this->category_id === $id;
    }

    /**
     * Связь с категорией.
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_category_assignments}}';
    }
}
