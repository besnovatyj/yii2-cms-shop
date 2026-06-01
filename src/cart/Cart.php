<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart;

use Besnovatyj\Shop\cart\cost\calculator\CalculatorInterface;
use Besnovatyj\Shop\cart\cost\Cost;
use Besnovatyj\Shop\cart\storage\StorageInterface;
use DomainException;

/**
 * Корзина покупателя.
 *
 * Lazy-загружает позиции из хранилища при первом обращении.
 */
class Cart
{
    /** @var CartItem[]|null */
    private ?array $items = null;

    /**
     * @param StorageInterface    $storage    Хранилище позиций.
     * @param CalculatorInterface $calculator Калькулятор стоимости.
     */
    public function __construct(
        private readonly StorageInterface    $storage,
        private readonly CalculatorInterface $calculator,
    ) {}

    /**
     * Возвращает все позиции корзины.
     *
     * @return CartItem[]
     */
    public function getItems(): array
    {
        $this->loadItems();
        return $this->items;
    }

    /**
     * Возвращает количество позиций в корзине.
     */
    public function getAmount(): int
    {
        $this->loadItems();
        return count($this->items);
    }

    /**
     * Добавляет позицию в корзину.
     * Если позиция уже существует — увеличивает количество.
     */
    public function add(CartItem $item): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() === $item->getId()) {
                $this->items[$i] = $current->plus($item->getQuantity());
                $this->saveItems();
                return;
            }
        }
        $this->items[] = $item;
        $this->saveItems();
    }

    /**
     * Устанавливает точное количество для позиции.
     *
     * @throws DomainException Если позиция не найдена.
     */
    public function set(string $id, int $quantity): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() === $id) {
                $this->items[$i] = $current->changeQuantity($quantity);
                $this->saveItems();
                return;
            }
        }
        throw new DomainException('Позиция корзины не найдена.');
    }

    /**
     * Удаляет позицию из корзины.
     *
     * @throws DomainException Если позиция не найдена.
     */
    public function remove(string $id): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() === $id) {
                unset($this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new DomainException('Позиция корзины не найдена.');
    }

    /**
     * Удаляет позицию по ID товара и модификации.
     *
     * @throws DomainException Если позиция не найдена.
     */
    public function removeByProduct(int $productId, ?int $modificationId): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getProductId() === $productId && $current->getModificationId() === $modificationId) {
                unset($this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new DomainException('Позиция корзины не найдена.');
    }

    /**
     * Очищает корзину.
     */
    public function clear(): void
    {
        $this->items = [];
        $this->saveItems();
    }

    /**
     * Возвращает расчитанную стоимость корзины.
     */
    public function getCost(): Cost
    {
        $this->loadItems();
        return $this->calculator->getCost($this->items);
    }

    /**
     * Возвращает суммарный вес корзины (граммы).
     */
    public function getWeight(): int
    {
        $this->loadItems();
        return array_sum(array_map(fn(CartItem $item) => $item->getWeight(), $this->items));
    }

    /**
     * Загружает позиции из хранилища (lazy).
     */
    private function loadItems(): void
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    /**
     * Сохраняет текущие позиции в хранилище.
     */
    private function saveItems(): void
    {
        $this->storage->save(array_values($this->items));
    }
}
