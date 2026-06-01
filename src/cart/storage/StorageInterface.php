<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\storage;

use Besnovatyj\Shop\cart\CartItem;

/**
 * Контракт хранилища корзины.
 */
interface StorageInterface
{
    /**
     * Загружает позиции из хранилища.
     *
     * @return CartItem[]
     */
    public function load(): array;

    /**
     * Сохраняет позиции в хранилище.
     *
     * @param CartItem[] $items
     */
    public function save(array $items): void;
}
