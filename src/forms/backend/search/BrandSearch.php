<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\search;

use Besnovatyj\Shop\entities\Brand;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска брендов.
 */
class BrandSearch extends Model
{
    public ?int    $id   = null;
    public ?string $name = null;
    public ?string $slug = null;

    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['name', 'slug'], 'safe'],
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
        $query = Brand::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
