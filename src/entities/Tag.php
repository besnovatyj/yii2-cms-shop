<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities;

use yii\db\ActiveRecord;

/**
 * Тег товара.
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 */
class Tag extends ActiveRecord
{
    /**
     * Создаёт новый тег.
     */
    public static function create(string $name, string $slug): self
    {
        $tag       = new static();
        $tag->name = $name;
        $tag->slug = $slug;
        return $tag;
    }

    /**
     * Редактирует тег.
     */
    public function edit(string $name, string $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'   => 'ID',
            'name' => 'Название',
            'slug' => 'Slug',
        ];
    }
}
