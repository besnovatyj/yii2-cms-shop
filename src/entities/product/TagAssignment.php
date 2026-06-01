<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use Besnovatyj\Shop\entities\Tag;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Связь товара с тегами.
 *
 * @property int $product_id
 * @property int $tag_id
 *
 * @property Tag $tag
 */
class TagAssignment extends ActiveRecord
{
    /**
     * Создаёт запись привязки.
     */
    public static function create(int $productId, int $tagId): self
    {
        $assignment             = new static();
        $assignment->product_id = $productId;
        $assignment->tag_id     = $tagId;
        return $assignment;
    }

    /**
     * Проверяет, относится ли привязка к указанному тегу.
     */
    public function isForTag(int $id): bool
    {
        return $this->tag_id === $id;
    }

    /**
     * Связь с тегом.
     */
    public function getTag(): ActiveQuery
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_tag_assignments}}';
    }
}
