<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\storage;

use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\entities\product\Product;
use yii\db\Connection;
use yii\db\Query;

/**
 * Хранилище корзины в базе данных (для авторизованных пользователей).
 */
class DbStorage implements StorageInterface
{
    /**
     * @param int        $userId ID пользователя.
     * @param Connection $db     Соединение с БД.
     */
    public function __construct(
        private readonly int        $userId,
        private readonly Connection $db,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function load(): array
    {
        $rows = (new Query())
            ->select('*')
            ->from('{{%shop_cart_items}}')
            ->where(['user_id' => $this->userId])
            ->orderBy(['product_id' => SORT_ASC, 'modification_id' => SORT_ASC])
            ->all($this->db);

        return array_values(array_filter(array_map(function (array $row): ?CartItem {
            /** @var Product|null $product */
            $product = Product::find()->active()->andWhere(['id' => $row['product_id']])->one();
            if (!$product) {
                return null;
            }
            return new CartItem($product, $row['modification_id'] ?: null, (int) $row['quantity']);
        }, $rows)));
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $items): void
    {
        $this->db->createCommand()
            ->delete('{{%shop_cart_items}}', ['user_id' => $this->userId])
            ->execute();

        if (!$items) {
            return;
        }

        $this->db->createCommand()->batchInsert(
            '{{%shop_cart_items}}',
            ['user_id', 'product_id', 'modification_id', 'quantity'],
            array_map(fn(CartItem $item) => [
                $this->userId,
                $item->getProductId(),
                $item->getModificationId(),
                $item->getQuantity(),
            ], $items),
        )->execute();
    }
}
