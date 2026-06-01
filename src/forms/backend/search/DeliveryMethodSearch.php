<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\search;

use Besnovatyj\Shop\entities\DeliveryMethod;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска методов доставки.
 */
class DeliveryMethodSearch extends Model
{
    public ?int    $id     = null;
    public ?string $name   = null;
    public ?int    $active = null;

    public function rules(): array
    {
        return [
            [['id', 'active'], 'integer'],
            ['name', 'safe'],
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
        $query = DeliveryMethod::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['sort' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'     => $this->id,
            'active' => $this->active,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
