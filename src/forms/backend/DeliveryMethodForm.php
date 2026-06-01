<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\DeliveryMethod;
use yii\base\Model;

/**
 * Форма создания/редактирования способа доставки.
 */
class DeliveryMethodForm extends BaseForm
{
    public string $name      = '';
    public int    $cost      = 0;
    public int    $minWeight = 0;
    public ?int   $maxWeight = null;
    public int    $sort      = 0;

    public function __construct(?DeliveryMethod $method = null, $config = [])
    {
        if ($method) {
            $this->name      = $method->name;
            $this->cost      = $method->cost;
            $this->minWeight = $method->min_weight;
            $this->maxWeight = $method->max_weight;
            $this->sort      = $method->sort;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            ['name', 'string', 'max' => 255],
            [['cost', 'minWeight', 'sort'], 'integer', 'min' => 0],
            ['maxWeight', 'integer', 'min' => 0],
            ['maxWeight', 'default', 'value' => null],
            ['maxWeight', 'compare', 'compareAttribute' => 'minWeight', 'operator' => '>=', 'when' => fn($model) => $model->maxWeight !== null],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name'      => 'Название',
            'cost'      => 'Стоимость',
            'minWeight' => 'Мин. вес (г)',
            'maxWeight' => 'Макс. вес (г), пусто = без ограничения',
            'sort'      => 'Сортировка',
        ];
    }
}
