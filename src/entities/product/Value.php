<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Значение характеристики для конкретного товара (EAV-паттерн).
 *
 * @property int    $product_id         Идентификатор продукта
 * @property int    $characteristic_id  Идентификатор характеристики
 * @property string $value              Значение
 *
 * @property Characteristic $characteristic
 */
class Value extends ActiveRecord
{
    /**
     * Создаёт новое значение характеристики.
     */
    public static function create(int $productId, int $characteristicId, string $value): self
    {
        $object                    = new static();
        $object->product_id        = $productId;
        $object->characteristic_id = $characteristicId;
        $object->value             = $value;
        return $object;
    }

    /**
     * Изменяет значение характеристики.
     */
    public function change(string $value): void
    {
        $this->value = $value;
    }

    /**
     * Проверяет, относится ли значение к указанной характеристике.
     */
    public function isForCharacteristic(int $id): bool
    {
        return $this->characteristic_id === $id;
    }

    /**
     * Связь с характеристикой.
     */
    public function getCharacteristic(): ActiveQuery
    {
        return $this->hasOne(Characteristic::class, ['id' => 'characteristic_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_values}}';
    }
}
