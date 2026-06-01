<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\product\Characteristic;

/**
 * Форма создания/редактирования характеристики.
 */
class CharacteristicForm extends BaseForm
{
    public string  $name     = '';
    public string  $type     = Characteristic::TYPE_STRING;
    public bool    $required = false;
    public ?string $default  = null;
    public array   $variants = [];
    public int     $sort     = 0;

    public function __construct(?Characteristic $characteristic = null, $config = [])
    {
        if ($characteristic) {
            $this->name     = $characteristic->name;
            $this->type     = $characteristic->type;
            $this->required = (bool) $characteristic->required;
            $this->default  = $characteristic->default;
            $this->variants = $characteristic->variants;
            $this->sort     = $characteristic->sort;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'default'], 'string', 'max' => 255],
            ['type', 'in', 'range' => [Characteristic::TYPE_STRING, Characteristic::TYPE_INTEGER, Characteristic::TYPE_FLOAT]],
            ['required', 'boolean'],
            ['sort', 'integer'],
            ['variants', 'each', 'rule' => ['string', 'max' => 255]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name'     => 'Название',
            'type'     => 'Тип',
            'required' => 'Обязательная',
            'default'  => 'По умолчанию',
            'variants' => 'Список значений',
            'sort'     => 'Сортировка',
        ];
    }
}
