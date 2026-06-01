<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\storage;

use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\entities\product\Product;
use Yii;
use yii\helpers\Json;
use yii\web\Cookie;

/**
 * Хранилище корзины в cookie (для гостей).
 *
 * Хранит только id товара/модификации и количество,
 * не сериализует объекты Product.
 */
class CookieStorage implements StorageInterface
{
    /**
     * @param string $key     Имя cookie.
     * @param int    $timeout Время жизни cookie (секунды).
     */
    public function __construct(
        private readonly string $key,
        private readonly int    $timeout,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function load(): array
    {
        $cookie = Yii::$app->request->cookies->get($this->key);
        if (!$cookie) {
            return [];
        }

        $rows = Json::decode($cookie->value);
        if (!is_array($rows)) {
            return [];
        }

        return array_values(array_filter(array_map(function (array $row): ?CartItem {
            if (!isset($row['p'], $row['q'])) {
                return null;
            }
            /** @var Product|null $product */
            $product = Product::find()->active()->andWhere(['id' => $row['p']])->one();
            if (!$product) {
                return null;
            }
            return new CartItem($product, $row['m'] ?? null, (int) $row['q']);
        }, $rows)));
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $items): void
    {
        Yii::$app->response->cookies->add(new Cookie([
            'name'   => $this->key,
            'value'  => Json::encode(array_map(fn(CartItem $item) => [
                'p' => $item->getProductId(),
                'm' => $item->getModificationId(),
                'q' => $item->getQuantity(),
            ], $items)),
            'expire' => time() + $this->timeout,
        ]));
    }
}
