<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities;

use Besnovatyj\Shop\entities\queries\DeliveryMethodQuery;
use yii\db\ActiveRecord;

/**
 * Способ доставки.
 *
 * @property int      $id
 * @property string   $name
 * @property int      $cost
 * @property int      $min_weight Минимальный вес (г)
 * @property int|null $max_weight Максимальный вес (г), null = без ограничения
 * @property bool     $active
 * @property int      $sort
 */
class DeliveryMethod extends ActiveRecord
{
    /**
     * Создаёт новый способ доставки.
     */
    public static function create(string $name, int $cost, int $minWeight, ?int $maxWeight, int $sort): self
    {
        $method             = new static();
        $method->name       = $name;
        $method->cost       = $cost;
        $method->min_weight = $minWeight;
        $method->max_weight = $maxWeight;
        $method->sort       = $sort;
        $method->active     = true;
        return $method;
    }

    /**
     * Редактирует способ доставки.
     */
    public function edit(string $name, int $cost, int $minWeight, ?int $maxWeight, int $sort): void
    {
        $this->name       = $name;
        $this->cost       = $cost;
        $this->min_weight = $minWeight;
        $this->max_weight = $maxWeight;
        $this->sort       = $sort;
    }

    /**
     * Активирует способ доставки.
     */
    public function activate(): void
    {
        $this->active = true;
    }

    /**
     * Деактивирует способ доставки.
     */
    public function deactivate(): void
    {
        $this->active = false;
    }

    /**
     * Проверяет, подходит ли способ доставки для указанного веса (г).
     */
    public function fitsWeight(int $weight): bool
    {
        if ($weight < $this->min_weight) {
            return false;
        }
        if ($this->max_weight !== null && $weight > $this->max_weight) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_delivery_methods}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function find(): DeliveryMethodQuery
    {
        return new DeliveryMethodQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'name'       => 'Название',
            'cost'       => 'Стоимость',
            'min_weight' => 'Мин. вес (г)',
            'max_weight' => 'Макс. вес (г)',
            'active'     => 'Активен',
            'sort'       => 'Сортировка',
        ];
    }
}
