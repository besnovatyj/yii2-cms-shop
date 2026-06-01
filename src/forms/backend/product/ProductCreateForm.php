<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Forms\CompositeForm;
use Besnovatyj\Meta\MetaForm;

/**
 * Форма создания товара.
 *
 * @property MetaForm       $meta
 * @property CategoriesForm $categories
 * @property TagsForm       $tags
 * @property PriceForm      $price
 * @property QuantityForm   $quantity
 */
class ProductCreateForm extends CompositeForm
{
    public ?int   $brandId     = null;
    public string $code        = '';
    public string $name        = '';
    public string $description = '';
    public int    $weight      = 0;

    /** @var array<int, string> Значения характеристик [characteristic_id => value] */
    public array $values = [];

    public function __construct($config = [])
    {
        $this->meta       = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->tags       = new TagsForm();
        $this->price      = new PriceForm();
        $this->quantity   = new QuantityForm();
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
        return ['meta', 'categories', 'tags', 'price', 'quantity'];
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
