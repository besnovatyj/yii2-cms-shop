<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\readModels;

use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\entities\product\Value;
use Besnovatyj\Shop\entities\Tag;
use Besnovatyj\Shop\forms\frontend\search\SearchForm;
use Besnovatyj\TreeManager\Manager\TreeQueryScope;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

/**
 * Репозиторий чтения для товаров (read-side).
 */
class ProductReadRepository
{
    private TreeQueryScope $treeScope;

    public function __construct()
    {
        $this->treeScope = new TreeQueryScope(Category::class);
    }

    /**
     * Итератор по всем активным товарам (для экспорта, индексации и т.п.).
     *
     * @return iterable<Product>
     */
    public function getAllIterator(): iterable
    {
        return Product::find()->alias('p')->active('p')->with('mainPhoto', 'brand')->each();
    }

    /**
     * Список всех активных товаров (с пагинацией).
     */
    public function getAll(): DataProviderInterface
    {
        return $this->getProvider(
            Product::find()->alias('p')->active('p')->with('mainPhoto')
        );
    }

    /**
     * Товары заданной категории и всех её потомков.
     */
    public function getAllByCategory(Category $category): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');

        $ids = $this->treeScope->descendantIds($category, andSelf: true);

        // Учитываем и основную категорию, и дополнительные (через categoryAssignments)
        $query->leftJoin('{{%shop_category_assignments}} ca', 'ca.product_id = p.id');
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    /**
     * Товары заданного бренда.
     */
    public function getAllByBrand(Brand $brand): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->andWhere(['p.brand_id' => $brand->id]);
        return $this->getProvider($query);
    }

    /**
     * Товары по тегу.
     */
    public function getAllByTag(Tag $tag): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->leftJoin('{{%shop_tag_assignments}} ta', 'ta.product_id = p.id');
        $query->andWhere(['ta.tag_id' => $tag->id]);
        $query->groupBy('p.id');
        return $this->getProvider($query);
    }

    /**
     * Последние N активных товаров (для виджета).
     *
     * @return Product[]
     */
    public function getFeatured(int $limit): array
    {
        return Product::find()->active()->with('mainPhoto')->orderBy(['id' => SORT_DESC])->limit($limit)->all();
    }

    /**
     * Ищет конкретный активный товар по ID.
     */
    public function find(int $id): ?Product
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();
    }

    /**
     * Поиск/фильтрация товаров по форме поиска.
     */
    public function search(SearchForm $form): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');

        if ($form->brand) {
            $query->andWhere(['p.brand_id' => $form->brand]);
        }

        if ($form->category) {
            $category = Category::findOne($form->category);
            if ($category) {
                $ids = $this->treeScope->descendantIds($category, andSelf: true);
                $query->leftJoin('{{%shop_category_assignments}} ca', 'ca.product_id = p.id');
                $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
            } else {
                $query->andWhere(['p.id' => 0]);
            }
        }

        // Фильтрация по значениям характеристик (EAV)
        if ($form->values) {
            $productIds = null;
            foreach ($form->values as $value) {
                if ($value->isFilled()) {
                    $q = Value::find()->andWhere(['characteristic_id' => $value->getId()]);
                    $q->andFilterWhere(['>=', 'CAST(value AS SIGNED)', $value->from]);
                    $q->andFilterWhere(['<=', 'CAST(value AS SIGNED)', $value->to]);
                    $q->andFilterWhere(['value' => $value->equal]);

                    $foundIds   = $q->select('product_id')->column();
                    $productIds = $productIds === null
                        ? $foundIds
                        : array_intersect($productIds, $foundIds);
                }
            }
            if ($productIds !== null) {
                $query->andWhere(['p.id' => $productIds]);
            }
        }

        if (!empty($form->text)) {
            $query->andWhere(['or',
                ['like', 'p.code', $form->text],
                ['like', 'p.name', $form->text],
            ]);
        }

        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    /**
     * Создаёт стандартный ActiveDataProvider.
     */
    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes'   => [
                    'id'     => ['asc' => ['p.id' => SORT_ASC],       'desc' => ['p.id' => SORT_DESC]],
                    'name'   => ['asc' => ['p.name' => SORT_ASC],     'desc' => ['p.name' => SORT_DESC]],
                    'price'  => ['asc' => ['p.price_new' => SORT_ASC],'desc' => ['p.price_new' => SORT_DESC]],
                    'rating' => ['asc' => ['p.rating' => SORT_ASC],   'desc' => ['p.rating' => SORT_DESC]],
                ],
            ],
            'pagination' => ['pageSizeLimit' => [15, 100]],
        ]);
    }
}
