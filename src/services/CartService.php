<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services;

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\repositories\ProductRepository;

/**
 * Сервис управления корзиной (фасад над Cart).
 */
class CartService
{
    public function __construct(
        private readonly Cart              $cart,
        private readonly ProductRepository $products,
    ) {}

    /**
     * Возвращает корзину.
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * Добавляет товар в корзину.
     *
     * @param int      $productId
     * @param int|null $modificationId
     * @param int      $quantity
     */
    public function add(int $productId, ?int $modificationId, int $quantity): void
    {
        $product = $this->products->get($productId);
        $this->cart->add(new CartItem($product, $modificationId, $quantity));
    }

    /**
     * Обновляет количество позиций корзины из массива [id => quantity].
     *
     * @param array<string, int> $quantityData
     */
    public function changeQuantity(array $quantityData): void
    {
        foreach ($quantityData as $id => $quantity) {
            $this->cart->set((string) $id, (int) $quantity);
        }
    }

    /**
     * Устанавливает точное количество для позиции.
     */
    public function set(string $id, int $quantity): void
    {
        $this->cart->set($id, $quantity);
    }

    /**
     * Удаляет позицию из корзины по строковому ключу.
     */
    public function remove(string $id): void
    {
        $this->cart->remove($id);
    }

    /**
     * Удаляет позицию из корзины по ID товара и модификации.
     */
    public function removeByProduct(int $productId, ?int $modificationId): void
    {
        $this->cart->removeByProduct($productId, $modificationId);
    }

    /**
     * Очищает корзину.
     */
    public function clear(): void
    {
        $this->cart->clear();
    }
}
