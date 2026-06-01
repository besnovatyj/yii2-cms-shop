<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\frontend;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\product\Modification;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Форма добавления товара в корзину.
 */
class AddToCartForm extends BaseForm
{
    public ?int $modification = null;
    public int  $quantity     = 1;

    private Product $_product;

    public function __construct(Product $product, array $config = [])
    {
        $this->_product = $product;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return array_values(array_filter([
            $this->_product->modifications ? ['modification', 'required'] : null,
            ['quantity', 'required'],
            ['modification', 'integer'],
            ['quantity', 'integer', 'min' => 1, 'max' => $this->_product->quantity],
        ]));
    }

    /**
     * Возвращает список модификаций для выпадающего списка.
     *
     * @return array<int, string>
     */
    public function modificationsList(): array
    {
        return ArrayHelper::map(
            $this->_product->modifications,
            'id',
            static function (Modification $modification) use (&$product): string {
                return $modification->code . ' — ' . $modification->name;
            }
        );
    }

    public function attributeLabels(): array
    {
        return [
            'modification' => 'Модификация',
            'quantity'     => 'Количество',
        ];
    }
}
