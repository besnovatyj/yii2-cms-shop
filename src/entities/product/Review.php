<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use DomainException;
use yii\db\ActiveRecord;

/**
 * Отзыв покупателя о товаре.
 *
 * @property int    $id
 * @property int    $product_id
 * @property int    $user_id
 * @property int    $created_at
 * @property int    $vote       Оценка (1..5)
 * @property string $text       Текст отзыва
 * @property int    $status     Статус (черновик / опубликован)
 */
class Review extends ActiveRecord
{
    public const int STATUS_DRAFT  = 0;
    public const int STATUS_ACTIVE = 1;

    /**
     * Создаёт новый отзыв.
     */
    public static function create(int $productId, int $userId, int $vote, string $text): self
    {
        $review             = new static();
        $review->product_id = $productId;
        $review->user_id    = $userId;
        $review->vote       = $vote;
        $review->text       = $text;
        $review->created_at = time();
        $review->status     = self::STATUS_DRAFT;
        return $review;
    }

    /**
     * Редактирует отзыв.
     */
    public function edit(int $vote, string $text): void
    {
        $this->vote = $vote;
        $this->text = $text;
    }

    /**
     * Публикует отзыв.
     *
     * @throws DomainException
     */
    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Отзыв уже опубликован.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * Снимает отзыв с публикации.
     *
     * @throws DomainException
     */
    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('Отзыв уже в черновике.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    /**
     * Проверяет, опубликован ли отзыв.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Проверяет, является ли отзыв черновиком.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Возвращает числовой рейтинг отзыва.
     */
    public function getRating(): int
    {
        return $this->vote;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_reviews}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'product_id' => 'Товар',
            'user_id'    => 'Пользователь',
            'created_at' => 'Дата создания',
            'vote'       => 'Оценка',
            'text'       => 'Текст отзыва',
            'status'     => 'Статус',
        ];
    }
}
