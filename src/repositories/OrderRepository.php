<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\order\Order;
use RuntimeException;
use Throwable;

/**
 * Репозиторий заказов.
 */
class OrderRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundException('Заказ не найден.');
        }
        return $order;
    }

    /**
     * @throws RuntimeException
     */
    public function save(Order $order): void
    {
        if (!$order->save()) {
            throw new RuntimeException('Ошибка сохранения заказа.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(Order $order): void
    {
        if (!$order->delete()) {
            throw new RuntimeException('Ошибка удаления заказа.');
        }
    }
}
