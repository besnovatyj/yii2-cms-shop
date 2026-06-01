<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\frontend\search;

use Besnovatyj\Forms\CompositeForm;
use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\entities\product\Characteristic;
use yii\helpers\ArrayHelper;

/**
 * Форма поиска товаров на фронтенде.
 *
 * @property ValueForm[] $values
 */
class SearchForm extends CompositeForm
{
    public ?string $text     = null;
    public ?int    $category = null;
    public ?int    $brand    = null;

    public function __construct(array $config = [])
    {
        /** @var Characteristic[] $characteristics */
        $characteristics = Characteristic::find()->orderBy('sort')->all();

        $this->values = array_map(
            static fn(Characteristic $c) => new ValueForm($c),
            $characteristics
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['text', 'string'],
            [['category', 'brand'], 'integer'],
        ];
    }

    /**
     * Возвращает список брендов для выпадающего списка.
     *
     * @return array<int, string>
     */
    public function brandsList(): array
    {
        return ArrayHelper::map(
            Brand::find()->orderBy('name')->asArray()->all(),
            'id',
            'name'
        );
    }

    public function formName(): string
    {
        return '';
    }

    public function attributeLabels(): array
    {
        return [
            'text'     => 'Поиск по названию',
            'category' => 'Категория',
            'brand'    => 'Бренд',
        ];
    }

    protected function internalForms(): array
    {
        return ['values'];
    }
}
