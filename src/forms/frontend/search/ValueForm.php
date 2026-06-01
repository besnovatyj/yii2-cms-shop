<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\frontend\search;

use Besnovatyj\Shop\entities\product\Characteristic;
use yii\base\Model;

/**
 * Форма значения одной характеристики в поиске.
 *
 * @property int $id
 */
class ValueForm extends Model
{
    public ?string $from  = null;
    public ?string $to    = null;
    public ?string $equal = null;

    private Characteristic $_characteristic;

    public function __construct(Characteristic $characteristic, array $config = [])
    {
        $this->_characteristic = $characteristic;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return array_values(array_filter([
            $this->_characteristic->isString()
                ? ['equal', 'string']
                : null,
            ($this->_characteristic->isInteger() || $this->_characteristic->isFloat())
                ? [['from', 'to'], 'number']
                : null,
        ]));
    }

    /**
     * Проверяет, заполнена ли форма хотя бы одним значением.
     */
    public function isFilled(): bool
    {
        return !empty($this->from) || !empty($this->to) || !empty($this->equal);
    }

    /**
     * Возвращает список вариантов для характеристик-справочников.
     *
     * @return array<string, string>
     */
    public function variantsList(): array
    {
        $variants = $this->_characteristic->variants;
        return $variants ? array_combine($variants, $variants) : [];
    }

    /**
     * Возвращает название характеристики.
     */
    public function getCharacteristicName(): string
    {
        return $this->_characteristic->name;
    }

    /**
     * Возвращает идентификатор характеристики.
     */
    public function getId(): int
    {
        return $this->_characteristic->id;
    }

    public function formName(): string
    {
        return 'v';
    }
}
