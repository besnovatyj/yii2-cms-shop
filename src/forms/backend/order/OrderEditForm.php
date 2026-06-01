<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\order;

use Besnovatyj\Forms\CompositeForm;
use Besnovatyj\Shop\entities\order\Order;

/**
 * Форма редактирования заказа.
 *
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderEditForm extends CompositeForm
{
    public ?string $note = null;

    public function __construct(Order $order, array $config = [])
    {
        $this->note     = $order->note;
        $this->delivery = new DeliveryForm($order);
        $this->customer = new CustomerForm($order);
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['note', 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }

    public function attributeLabels(): array
    {
        return [
            'note' => 'Примечание',
        ];
    }
}
