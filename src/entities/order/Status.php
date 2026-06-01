<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\order;

/**
 * Статус заказа (Value Object).
 */
final class Status
{
    public const int NEW                 = 1;
    public const int PAID                = 2;
    public const int SENT                = 3;
    public const int COMPLETED           = 4;
    public const int CANCELLED           = 5;
    public const int CANCELLED_BY_CUSTOMER = 6;

    /** @var array<int, string> Метки статусов */
    private const array LABELS = [
        self::NEW                 => 'Новый',
        self::PAID                => 'Оплачен',
        self::SENT                => 'Отправлен',
        self::COMPLETED           => 'Завершён',
        self::CANCELLED           => 'Отменён',
        self::CANCELLED_BY_CUSTOMER => 'Отменён покупателем',
    ];

    public function __construct(
        public readonly int $value,
        public readonly int $created_at,
    ) {}

    /**
     * Возвращает текстовую метку статуса.
     */
    public function getLabel(): string
    {
        return self::LABELS[$this->value] ?? 'Неизвестно';
    }

    /**
     * Возвращает список всех статусов для выпадающего списка.
     *
     * @return array<int, string>
     */
    public static function getLabels(): array
    {
        return self::LABELS;
    }
}
