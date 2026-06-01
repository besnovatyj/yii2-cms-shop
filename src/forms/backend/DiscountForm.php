<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\Discount;
use yii\base\Model;

/**
 * Форма создания/редактирования скидки.
 */
class DiscountForm extends BaseForm
{
    public int     $percent  = 0;
    public string  $name     = '';
    public ?string $fromDate = null;
    public ?string $toDate   = null;
    public int     $sort     = 0;

    public function __construct(?Discount $discount = null, $config = [])
    {
        if ($discount) {
            $this->percent  = $discount->percent;
            $this->name     = $discount->name;
            $this->fromDate = $discount->from_date;
            $this->toDate   = $discount->to_date;
            $this->sort     = $discount->sort;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            ['name', 'string', 'max' => 255],
            ['percent', 'integer', 'min' => 1, 'max' => 100],
            [['fromDate', 'toDate'], 'date', 'format' => 'php:Y-m-d'],
            ['sort', 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'percent'  => 'Процент',
            'name'     => 'Название',
            'fromDate' => 'Дата начала',
            'toDate'   => 'Дата окончания',
            'sort'     => 'Сортировка',
        ];
    }
}
