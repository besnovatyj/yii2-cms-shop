<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities;

use Besnovatyj\Shop\entities\queries\DiscountQuery;
use yii\db\ActiveRecord;

/**
 * Скидка (применяется к стоимости корзины через DynamicCost).
 *
 * @property int         $id
 * @property int         $percent   Процент скидки
 * @property string      $name      Название скидки
 * @property string|null $from_date Дата начала действия скидки
 * @property string|null $to_date   Дата окончания действия скидки
 * @property bool        $active    Признак активности
 * @property int         $sort      Сортировка
 */
class Discount extends ActiveRecord
{
    /**
     * Создаёт новую скидку.
     */
    public static function create(int $percent, string $name, ?string $fromDate, ?string $toDate, int $sort): self
    {
        $discount            = new static();
        $discount->percent   = $percent;
        $discount->name      = $name;
        $discount->from_date = $fromDate;
        $discount->to_date   = $toDate;
        $discount->sort      = $sort;
        $discount->active    = true;
        return $discount;
    }

    /**
     * Редактирует скидку.
     */
    public function edit(int $percent, string $name, ?string $fromDate, ?string $toDate, int $sort): void
    {
        $this->percent   = $percent;
        $this->name      = $name;
        $this->from_date = $fromDate;
        $this->to_date   = $toDate;
        $this->sort      = $sort;
    }

    /**
     * Активирует скидку.
     */
    public function activate(): void
    {
        $this->active = true;
    }

    /**
     * Деактивирует скидку.
     */
    public function deactivate(): void
    {
        $this->active = false;
    }

    /**
     * Проверяет, действует ли скидка сейчас.
     * Учитывает даты начала и окончания, если они заданы.
     */
    public function isEnabled(): bool
    {
        if (!$this->active) {
            return false;
        }
        $now = date('Y-m-d');
        if ($this->from_date && $now < $this->from_date) {
            return false;
        }
        if ($this->to_date && $now > $this->to_date) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_discounts}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function find(): DiscountQuery
    {
        return new DiscountQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'        => 'ID',
            'percent'   => 'Процент',
            'name'      => 'Название',
            'from_date' => 'Дата начала',
            'to_date'   => 'Дата окончания',
            'active'    => 'Активна',
            'sort'      => 'Сортировка',
        ];
    }
}
