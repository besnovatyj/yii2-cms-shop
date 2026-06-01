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
 * Форма цены товара.
 */
class PriceForm extends BaseForm
{
    public int $new = 0;
    public int $old = 0;

    public function __construct(?Product $product = null, $config = [])
    {
        if ($product) {
            $this->new = $product->price_new;
            $this->old = $product->price_old;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['new'], 'required'],
            [['new', 'old'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'new' => 'Цена',
            'old' => 'Старая цена',
        ];
    }
}
