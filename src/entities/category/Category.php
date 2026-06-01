<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\category;

use Besnovatyj\Meta\Meta;
use Besnovatyj\Meta\MetaBehavior;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\TreeManager\Manager\entities\Node;
use yii\db\ActiveQuery;

/**
 * Категория товаров (иерархическая, Nested Set через TreeModule).
 *
 * @property int    $id
 * @property int    $lft
 * @property int    $rgt
 * @property int    $depth
 * @property int    $tree
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int    $status
 * @property int    $sort_order  Сортировка корневых узлов
 *
 * @property Meta   $meta
 *
 * @mixin MetaBehavior
 */
class Category extends Node
{
    public Meta $meta;

    /**
     * Создаёт новую категорию.
     */
    public static function create(string $name, string $slug, ?string $description, Meta $meta): self
    {
        $category              = new static();
        $category->name        = $name;
        $category->slug        = $slug;
        $category->description = $description;
        $category->meta        = $meta;
        return $category;
    }

    /**
     * Редактирует категорию.
     */
    public function edit(string $name, string $slug, ?string $description, Meta $meta): void
    {
        $this->name        = $name;
        $this->slug        = $slug;
        $this->description = $description;
        $this->meta        = $meta;
    }

    /**
     * Возвращает SEO-заголовок (meta title или название категории).
     */
    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->name;
    }

    /**
     * Переключает статус отображения категории.
     */
    public function changeStatus(): void
    {
        $this->status = ((int) $this->status) === 1 ? 0 : 1;
    }

    /**
     * Возвращает количество товаров в категории.
     */
    public function countProducts(): int|string|null
    {
        return $this->getProducts()->count();
    }

    /**
     * Связь с товарами категории.
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_categories}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            ...parent::behaviors(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
