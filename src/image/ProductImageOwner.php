<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\image;

use Besnovatyj\Images\contracts\ImageOwnerInterface;
use Besnovatyj\Shop\entities\product\Photo;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\repositories\ProductRepository;
use yii\db\Exception;

/**
 * Адаптер Product к ImageOwnerInterface.
 *
 * Реализует pessimistic lock через PessimisticLockBehavior Product,
 * чтобы исключить race condition при параллельной загрузке фотографий.
 */
readonly class ProductImageOwner implements ImageOwnerInterface
{
    public function __construct(
        private Product           $product,
        private ProductRepository $repository,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getOwnerId(): int
    {
        return $this->product->id;
    }

    /**
     * {@inheritdoc}
     *
     * @return Photo[]
     */
    public function getOwnedImages(): array
    {
        return $this->product->photos;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainImageId(): ?int
    {
        return $this->product->main_photo_id ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function setMainImageId(?int $imageId): void
    {
        $this->product->setMainPhoto($imageId);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function saveOwner(): void
    {
        $this->repository->save($this->product);
    }

    /**
     * Блокирует строку товара (SELECT FOR UPDATE) до конца транзакции.
     * Предотвращает race condition при параллельной загрузке нескольких фото.
     *
     * @throws Exception
     */
    public function lockOwner(): void
    {
        $this->product->lock();
    }

    /**
     * Обновляет данные товара из БД после применения блокировки.
     */
    public function refreshOwner(): void
    {
        $this->product->refresh();
    }
}
