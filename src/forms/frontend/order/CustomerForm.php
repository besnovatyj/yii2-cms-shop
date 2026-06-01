<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\frontend\order;

use yii\base\Model;

/**
 * Форма данных покупателя при оформлении заказа.
 */
class CustomerForm extends Model
{
    public string $phone = '';
    public string $name  = '';

    public function rules(): array
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => 'Телефон',
            'name'  => 'Имя',
        ];
    }
}
