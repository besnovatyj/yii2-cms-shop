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
use Besnovatyj\TreeManager\Manager\TreeQueryScope;

/**
 * Форма выбора категорий товара.
 */
class CategoriesForm extends BaseForm
{
    /** @var int|null Основная категория */
    public ?int $main = null;

    /**
     * Дополнительные категории.
     *
     * НЕ типизируем как `array`: `checkboxList` при пустом выборе шлёт скрытый fallback `''`, а
     * `BaseForm` приводит только скалярные типы — строка попала бы в typed-`array` → TypeError.
     * Нормализуется правилом `default value=[]`. (Как в модуле catalog.)
     *
     * @var int[]
     */
    public $others = [];

    public function __construct(?Product $product = null, $config = [])
    {
        if ($product) {
            $this->main = $product->category_id;
            $this->others = array_map(
                fn(CategoryAssignment $a) => $a->category_id,
                $product->categoryAssignments,
            );
        }
        parent::__construct($config);
    }

    /**
     * Возвращает список категорий для выпадающих списков (всё дерево с отступами).
     *
     * Используем стандартный {@see TreeQueryScope::dropdownTree()} tree-manager'а — как в
     * catalog/blog/menu. Он включает и корневые узлы (depth 0) и сортирует по `sort_order` + `lft`.
     * Ручной запрос с `depth > 0` подходил только для одно-корневого дерева (эталон Елисеева) и в
     * много-корневом CMS-дереве отсекал все корневые категории.
     *
     * @return array<int, string>
     */
    public function categoriesList(): array
    {
        return (new TreeQueryScope(Category::class))->dropdownTree();
    }

    public function rules(): array
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'default', 'value' => []],
            ['others', 'each', 'rule' => ['integer']],
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
