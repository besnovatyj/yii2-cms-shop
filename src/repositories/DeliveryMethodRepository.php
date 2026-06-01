<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\DeliveryMethod;
use RuntimeException;
use Throwable;

/**
 * Репозиторий способов доставки.
 */
class DeliveryMethodRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): DeliveryMethod
    {
        if (!$method = DeliveryMethod::findOne($id)) {
            throw new NotFoundException('Способ доставки не найден.');
        }
        return $method;
    }

    /**
     * Возвращает все активные способы доставки.
     *
     * @return DeliveryMethod[]
     */
    public function findActive(): array
    {
        return DeliveryMethod::find()->active()->orderBy(['sort' => SORT_ASC])->all();
    }

    /**
     * @throws RuntimeException
     */
    public function save(DeliveryMethod $method): void
    {
        if (!$method->save()) {
            throw new RuntimeException('Ошибка сохранения способа доставки.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(DeliveryMethod $method): void
    {
        if (!$method->delete()) {
            throw new RuntimeException('Ошибка удаления способа доставки.');
        }
    }
}
