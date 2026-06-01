<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\search;

use Besnovatyj\Shop\entities\product\Product;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска/фильтрации товаров в административном разделе.
 */
class ProductSearch extends Model
{
    public ?int    $id          = null;
    public ?string $code        = null;
    public ?string $name        = null;
    public ?int    $category_id = null;
    public ?int    $brand_id    = null;
    public ?int    $status      = null;

    public function rules(): array
    {
        return [
            [['id', 'category_id', 'brand_id', 'status'], 'integer'],
            [['code', 'name'], 'safe'],
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
        $query = Product::find()->with('mainPhoto');

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
            'id'        => $this->id,
            'brand_id'  => $this->brand_id,
            'status'    => $this->status,
        ]);

        if ($this->category_id) {
            $query->innerJoin(
                'shop_category_assignments ca',
                'ca.product_id = {{%shop_products}}.id AND ca.category_id = :cid',
                [':cid' => $this->category_id]
            );
        }

        $query
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Возвращает список статусов товара.
     *
     * @return array<int, string>
     */
    public function statusList(): array
    {
        return [
            Product::STATUS_DRAFT  => 'Черновик',
            Product::STATUS_ACTIVE => 'Активен',
        ];
    }
}
