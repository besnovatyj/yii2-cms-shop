<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart;

use Besnovatyj\Shop\entities\product\Modification;
use Besnovatyj\Shop\entities\product\Product;
use DomainException;

/**
 * Позиция корзины.
 *
 * Иммутабельный value-объект: plus() и changeQuantity() возвращают новый экземпляр.
 */
class CartItem
{
    /**
     * @param Product  $product        Товар.
     * @param int|null $modificationId ID модификации или null.
     * @param int      $quantity       Количество.
     * @throws DomainException Если товара недостаточно.
     */
    public function __construct(
        private readonly Product $product,
        private readonly ?int    $modificationId,
        private readonly int     $quantity,
    ) {
        if (!$product->canBeCheckout($modificationId, $quantity)) {
            throw new DomainException('Запрошенное количество превышает остаток.');
        }
    }

    /**
     * Уникальный идентификатор позиции (товар + модификация).
     */
    public function getId(): string
    {
        return md5(serialize([$this->product->id, $this->modificationId]));
    }

    /**
     * Возвращает ID товара.
     */
    public function getProductId(): int
    {
        return $this->product->id;
    }

    /**
     * Возвращает объект товара.
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Возвращает ID модификации или null.
     */
    public function getModificationId(): ?int
    {
        return $this->modificationId;
    }

    /**
     * Возвращает объект модификации или null.
     */
    public function getModification(): ?Modification
    {
        if ($this->modificationId === null) {
            return null;
        }
        foreach ($this->product->modifications as $modification) {
            if ($modification->id === $this->modificationId) {
                return $modification;
            }
        }
        return null;
    }

    /**
     * Возвращает количество.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Возвращает масса позиции (товар × количество).
     */
    public function getWeight(): int
    {
        return $this->product->weight * $this->quantity;
    }

    /**
     * Возвращает стоимость позиции (цена × количество).
     */
    public function getCost(): int
    {
        return $this->getPrice() * $this->quantity;
    }

    /**
     * Возвращает цену единицы товара (с учётом модификации).
     */
    public function getPrice(): int
    {
        if ($this->modificationId !== null) {
            foreach ($this->product->modifications as $modification) {
                if ($modification->id === $this->modificationId) {
                    return $modification->price ?: $this->product->price_new;
                }
            }
        }
        return (int) $this->product->price_new;
    }

    /**
     * Возвращает новую позицию с увеличенным количеством.
     */
    public function plus(int $quantity): self
    {
        return new self($this->product, $this->modificationId, $this->quantity + $quantity);
    }

    /**
     * Возвращает новую позицию с изменённым количеством.
     */
    public function changeQuantity(int $quantity): self
    {
        return new self($this->product, $this->modificationId, $quantity);
    }
}
