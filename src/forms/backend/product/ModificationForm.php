<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\product\Modification;
use yii\base\Model;

/**
 * Форма создания/редактирования модификации товара.
 */
class ModificationForm extends BaseForm
{
    public string $code     = '';
    public string $name     = '';
    public int    $price    = 0;
    public int    $quantity = 0;

    public function __construct(?Modification $modification = null, $config = [])
    {
        if ($modification) {
            $this->code     = $modification->code;
            $this->name     = $modification->name;
            $this->price    = $modification->price;
            $this->quantity = $modification->quantity;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['price', 'quantity'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'code'     => 'Артикул',
            'name'     => 'Название',
            'price'    => 'Цена',
            'quantity' => 'Количество',
        ];
    }
}
