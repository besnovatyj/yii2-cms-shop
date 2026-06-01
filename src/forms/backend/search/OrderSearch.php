<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\search;

use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\entities\order\Status;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска заказов.
 */
class OrderSearch extends Model
{
    public ?int $id     = null;
    public ?int $status = null;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
        ];
    }

    /**
     * Выполняет поиск и возвращает провайдер данных.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'         => $this->id,
            'current_status' => $this->status,
        ]);

        return $dataProvider;
    }

    /**
     * Возвращает список статусов заказа.
     *
     * @return array<int, string>
     */
    public function statusList(): array
    {
        return Status::getLabels();
    }
}
