<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Forms\CompositeForm;
use Besnovatyj\Meta\MetaForm;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\entities\product\Value;

/**
 * Форма редактирования товара.
 *
 * @property MetaForm       $meta
 * @property CategoriesForm $categories
 * @property TagsForm       $tags
 */
class ProductEditForm extends CompositeForm
{
    public ?int    $brandId     = null;
    public string  $code        = '';
    public string  $name        = '';
    public ?string $description = null;
    public int     $weight      = 0;

    /** @var array<int, string> Значения характеристик [characteristic_id => value] */
    public array $values = [];

    public function __construct(?Product $product = null, $config = [])
    {
        if ($product) {
            $this->brandId     = $product->brand_id;
            $this->code        = $product->code;
            $this->name        = $product->name;
            $this->description = $product->description;
            $this->weight      = $product->weight;
            $this->meta        = new MetaForm($product->meta);
            $this->categories  = new CategoriesForm($product);
            $this->tags        = new TagsForm($product);

            /** @var Value[] $existingValues */
            $existingValues = Value::find()->andWhere(['product_id' => $product->id])->all();
            foreach ($existingValues as $value) {
                $this->values[$value->characteristic_id] = (string) $value->value;
            }
        } else {
            $this->meta       = new MetaForm();
            $this->categories = new CategoriesForm();
            $this->tags       = new TagsForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            [['name', 'code'], 'string', 'max' => 255],
            ['description', 'string'],
            ['brandId', 'integer'],
            ['weight', 'integer', 'min' => 0],
            ['values', 'safe'],
        ];
    }

    protected function internalForms(): array
    {
        return ['meta', 'categories', 'tags'];
    }

    public function attributeLabels(): array
    {
        return [
            'brandId'     => 'Бренд',
            'code'        => 'Артикул',
            'name'        => 'Название',
            'description' => 'Описание',
            'weight'      => 'Масса (г)',
        ];
    }
}
