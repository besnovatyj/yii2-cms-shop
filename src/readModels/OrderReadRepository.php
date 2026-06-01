<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\readModels;

use Besnovatyj\Shop\entities\order\Order;
use yii\data\ActiveDataProvider;

/**
 * Репозиторий чтения для заказов (read-side).
 */
class OrderReadRepository
{
    /**
     * Список заказов с пагинацией.
     */
    public function getAll(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()->orderBy(['id' => SORT_DESC]),
        ]);
    }

    /**
     * Список заказов пользователя с пагинацией.
     */
    public function getAllByUser(int $userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()->andWhere(['user_id' => $userId])->orderBy(['id' => SORT_DESC]),
        ]);
    }
}
