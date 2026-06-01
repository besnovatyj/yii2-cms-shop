<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\order;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\DeliveryMethod;
use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Форма редактирования доставки в заказе.
 */
class DeliveryForm extends BaseForm
{
    public ?int   $method  = null;
    public string $index   = '';
    public string $address = '';

    public function __construct(Order $order, array $config = [])
    {
        $this->method  = $order->delivery_method_id;
        $this->index   = $order->deliveryData->index;
        $this->address = $order->deliveryData->address;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['method', 'integer'],
            [['index', 'address'], 'required'],
            ['index', 'string', 'max' => 255],
            ['address', 'string'],
        ];
    }

    /**
     * Возвращает список методов доставки для выпадающего списка.
     *
     * @return array<int, string>
     */
    public function deliveryMethodsList(): array
    {
        /** @var DeliveryMethod[] $methods */
        $methods = DeliveryMethod::find()->orderBy('sort')->all();

        return ArrayHelper::map($methods, 'id', static function (DeliveryMethod $method): string {
            return $method->name . ' (' . PriceHelper::format($method->cost) . ')';
        });
    }

    public function attributeLabels(): array
    {
        return [
            'method'  => 'Метод доставки',
            'index'   => 'Индекс',
            'address' => 'Адрес',
        ];
    }
}
