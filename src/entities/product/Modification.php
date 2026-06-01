<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use DomainException;
use yii\db\ActiveRecord;

/**
 * Модификация (вариация) товара.
 *
 * @property int    $id
 * @property int    $product_id
 * @property string $code       Артикул модификации
 * @property string $name       Название модификации
 * @property int    $price      Цена модификации (переопределяет цену товара; 0 = использовать цену товара)
 * @property int    $quantity   Остаток
 */
class Modification extends ActiveRecord
{
    /**
     * Создаёт новую модификацию.
     */
    public static function create(int $productId, string $code, string $name, int $price, int $quantity): self
    {
        $modification             = new static();
        $modification->product_id = $productId;
        $modification->code       = $code;
        $modification->name       = $name;
        $modification->price      = $price;
        $modification->quantity   = $quantity;
        return $modification;
    }

    /**
     * Редактирует модификацию.
     */
    public function edit(string $code, string $name, int $price, int $quantity): void
    {
        $this->code     = $code;
        $this->name     = $name;
        $this->price    = $price;
        $this->quantity = $quantity;
    }

    /**
     * Уменьшает остаток при оформлении заказа.
     *
     * @throws DomainException Если запрошенное количество больше остатка.
     */
    public function checkout(int $quantity): void
    {
        if ($quantity > $this->quantity) {
            throw new DomainException("Доступно только {$this->quantity} единиц.");
        }
        $this->quantity -= $quantity;
    }

    /**
     * Проверяет, является ли данная запись модификацией с указанным кодом.
     */
    public function isCodeEqualTo(string $code): bool
    {
        return $this->code === $code;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_modifications}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'       => 'ID',
            'code'     => 'Артикул',
            'name'     => 'Название',
            'price'    => 'Цена',
            'quantity' => 'Количество',
        ];
    }
}
