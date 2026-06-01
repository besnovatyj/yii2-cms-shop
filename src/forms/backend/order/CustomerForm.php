<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\order;

use Besnovatyj\Shop\entities\order\Order;
use yii\base\Model;

/**
 * Форма редактирования данных покупателя в заказе.
 */
class CustomerForm extends Model
{
    public string $phone = '';
    public string $name  = '';

    public function __construct(Order $order, array $config = [])
    {
        $this->phone = $order->customerData->phone;
        $this->name  = $order->customerData->name;
        parent::__construct($config);
    }

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
