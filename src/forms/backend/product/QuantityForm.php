<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\product\Product;
use yii\base\Model;

/**
 * Форма количества товара.
 */
class QuantityForm extends BaseForm
{
    public int $quantity = 0;

    public function __construct(?Product $product = null, $config = [])
    {
        if ($product) {
            $this->quantity = $product->quantity;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['quantity', 'required'],
            ['quantity', 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'quantity' => 'Количество',
        ];
    }
}
