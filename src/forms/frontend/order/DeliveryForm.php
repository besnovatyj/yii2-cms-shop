<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\frontend\order;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\DeliveryMethod;
use Besnovatyj\Shop\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Форма выбора доставки при оформлении заказа.
 */
class DeliveryForm extends BaseForm
{
    public ?int   $method  = null;
    public string $index   = '';
    public string $address = '';

    private int $_weight;

    public function __construct(int $weight, array $config = [])
    {
        $this->_weight = $weight;
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
     * Возвращает список методов доставки, доступных для данного веса.
     *
     * @return array<int, string>
     */
    public function deliveryMethodsList(): array
    {
        /** @var DeliveryMethod[] $methods */
        $methods = DeliveryMethod::find()
            ->active()
            ->andWhere(['<=', 'min_weight', $this->_weight])
            ->andWhere([
                'or',
                ['max_weight' => null],
                ['>=', 'max_weight', $this->_weight],
            ])
            ->orderBy('sort')
            ->all();

        return ArrayHelper::map($methods, 'id', static function (DeliveryMethod $method): string {
            return $method->name . ' (' . PriceHelper::format($method->cost) . ')';
        });
    }

    public function attributeLabels(): array
    {
        return [
            'method'  => 'Метод доставки',
            'index'   => 'Почтовый индекс',
            'address' => 'Адрес',
        ];
    }
}
