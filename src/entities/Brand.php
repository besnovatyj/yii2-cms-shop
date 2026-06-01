<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities;

use yii\db\ActiveRecord;

/**
 * Бренд товара.
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $logo
 * @property int    $sort
 */
class Brand extends ActiveRecord
{
    /**
     * Создаёт новый бренд.
     */
    public static function create(string $name, string $slug, ?string $description, ?string $logo, int $sort): self
    {
        $brand              = new static();
        $brand->name        = $name;
        $brand->slug        = $slug;
        $brand->description = $description;
        $brand->logo        = $logo;
        $brand->sort        = $sort;
        return $brand;
    }

    /**
     * Редактирует бренд.
     */
    public function edit(string $name, string $slug, ?string $description, ?string $logo, int $sort): void
    {
        $this->name        = $name;
        $this->slug        = $slug;
        $this->description = $description;
        $this->logo        = $logo;
        $this->sort        = $sort;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_brands}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'name'        => 'Название',
            'slug'        => 'Slug',
            'description' => 'Описание',
            'logo'        => 'Логотип',
            'sort'        => 'Сортировка',
        ];
    }
}
