<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\Shop\entities\product\CategoryAssignment;
use Besnovatyj\Shop\entities\product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Форма выбора категорий товара.
 */
class CategoriesForm extends BaseForm
{
    /** @var int|null Основная категория */
    public ?int $main = null;

    /** @var int[] Дополнительные категории */
    public array $others = [];

    /** @var int[] Алиас для вьюх */
    public array $additional = [];

    public function __construct(?Product $product = null, $config = [])
    {
        if ($product) {
            $this->main = $product->category_id;
            $this->others = array_map(
                fn(CategoryAssignment $a) => $a->category_id,
                $product->categoryAssignments,
            );
            $this->additional = $this->others;
        }
        parent::__construct($config);
    }

    /**
     * Возвращает список категорий для выпадающих списков.
     *
     * @return array<int, string>
     */
    public function categoriesList(): array
    {
        return ArrayHelper::map(
            Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(),
            'id',
            static function (array $category): string {
                $indent = $category['depth'] > 1 ? str_repeat('— ', (int) $category['depth'] - 1) . ' ' : '';
                return $indent . $category['name'];
            }
        );
    }

    public function rules(): array
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']],
            ['additional', 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'main'   => 'Основная категория',
            'others' => 'Дополнительные категории',
        ];
    }
}
