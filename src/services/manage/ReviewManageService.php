<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\product\Review;
use Besnovatyj\Shop\forms\backend\product\ReviewEditForm;
use Besnovatyj\Shop\repositories\ProductRepository;
use DomainException;
use RuntimeException;
use Throwable;

/**
 * Сервис управления отзывами о товарах.
 */
class ReviewManageService
{
    public function __construct(
        private readonly ProductRepository $products,
    ) {}

    /**
     * Публикует отзыв и пересчитывает рейтинг товара.
     *
     * @throws DomainException
     * @throws RuntimeException
     */
    public function activate(int $productId, int $reviewId): void
    {
        $review = $this->getReview($productId, $reviewId);
        $review->activate();

        if (!$review->save()) {
            throw new RuntimeException('Ошибка сохранения отзыва.');
        }

        $this->recalculateRating($productId);
    }

    /**
     * Переводит отзыв в черновик и пересчитывает рейтинг.
     *
     * @throws DomainException
     * @throws RuntimeException
     */
    public function draft(int $productId, int $reviewId): void
    {
        $review = $this->getReview($productId, $reviewId);
        $review->draft();

        if (!$review->save()) {
            throw new RuntimeException('Ошибка сохранения отзыва.');
        }

        $this->recalculateRating($productId);
    }

    /**
     * Редактирует отзыв (из бэкэнда).
     *
     * @throws RuntimeException
     */
    public function edit(int $productId, int $reviewId, ReviewEditForm $form): void
    {
        $review = $this->getReview($productId, $reviewId);
        $review->edit($form->vote, $form->text);

        if (!$review->save()) {
            throw new RuntimeException('Ошибка сохранения отзыва.');
        }
    }

    /**
     * Удаляет отзыв.
     *
     * @throws Throwable
     */
    public function remove(int $productId, int $reviewId): void
    {
        $review = $this->getReview($productId, $reviewId);

        if (!$review->delete()) {
            throw new RuntimeException('Ошибка удаления отзыва.');
        }

        $this->recalculateRating($productId);
    }

    /**
     * Создаёт отзыв от пользователя и пересчитывает рейтинг.
     *
     * @throws RuntimeException
     */
    public function create(int $productId, int $userId, int $vote, string $text): Review
    {
        $review = Review::create($productId, $userId, $vote, $text);

        if (!$review->save()) {
            throw new RuntimeException('Ошибка создания отзыва.');
        }

        return $review;
    }

    // ── Private ───────────────────────────────────────────────────────────

    /**
     * @throws DomainException
     */
    private function getReview(int $productId, int $reviewId): Review
    {
        $review = Review::find()
            ->andWhere(['id' => $reviewId, 'product_id' => $productId])
            ->one();

        if (!$review) {
            throw new DomainException('Отзыв не найден.');
        }

        return $review;
    }

    /**
     * Пересчитывает средний рейтинг товара из активных отзывов.
     */
    private function recalculateRating(int $productId): void
    {
        /** @var Review[] $activeReviews */
        $activeReviews = Review::find()
            ->andWhere(['product_id' => $productId, 'status' => Review::STATUS_ACTIVE])
            ->all();

        $product = $this->products->get($productId);
        $product->recalculateRating($activeReviews);
        $this->products->save($product);
    }
}
