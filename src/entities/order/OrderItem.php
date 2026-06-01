<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\order;

use Besnovatyj\Shop\entities\product\Modification;
use Besnovatyj\Shop\entities\product\Product;
use yii\db\ActiveRecord;

/**
 * Позиция заказа.
 *
 * @property int         $id
 * @property int         $order_id
 * @property int         $product_id
 * @property int|null    $modification_id
 * @property string      $product_name
 * @property string      $product_code
 * @property string|null $modification_name
 * @property string|null $modification_code
 * @property int         $price
 * @property int         $quantity
 */
class OrderItem extends ActiveRecord
{
    /**
     * Создаёт позицию из товара.
     */
    public static function create(Product $product, ?int $modificationId, int $price, int $quantity): static
    {
        $item                  = new static();
        $item->product_id      = $product->id;
        $item->product_name    = $product->name;
        $item->product_code    = $product->code;
        $item->price           = $price;
        $item->quantity        = $quantity;

        if ($modificationId !== null) {
            /** @var Modification $modification */
            $modification               = $product->getModifications()
                ->andWhere(['id' => $modificationId])
                ->one();
            $item->modification_id      = $modification->id;
            $item->modification_name    = $modification->name;
            $item->modification_code    = $modification->code;
        }

        return $item;
    }

    /**
     * Возвращает стоимость позиции (цена × количество).
     */
    public function getCost(): int
    {
        return $this->price * $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_order_items}}';
    }
}
