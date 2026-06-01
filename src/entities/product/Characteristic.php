<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Характеристика (атрибут), которую можно задать у товара.
 *
 * @property int         $id
 * @property string      $name      Название
 * @property string      $type      Тип значения (string|integer|float)
 * @property bool        $required  Обязательная ли характеристика
 * @property string|null $default   Значение по умолчанию
 * @property array       $variants  Список допустимых значений (для выпадающего списка)
 * @property int         $sort      Сортировка
 */
class Characteristic extends ActiveRecord
{
    public const string TYPE_STRING  = 'string';
    public const string TYPE_INTEGER = 'integer';
    public const string TYPE_FLOAT   = 'float';

    public array $variants = [];

    /**
     * Создаёт новую характеристику.
     */
    public static function create(string $name, string $type, bool $required, ?string $default, array $variants, int $sort): self
    {
        $object           = new static();
        $object->name     = $name;
        $object->type     = $type;
        $object->required = $required;
        $object->default  = $default;
        $object->variants = $variants;
        $object->sort     = $sort;
        return $object;
    }

    /**
     * Редактирует характеристику.
     */
    public function edit(string $name, string $type, bool $required, ?string $default, array $variants, int $sort): void
    {
        $this->name     = $name;
        $this->type     = $type;
        $this->required = $required;
        $this->default  = $default;
        $this->variants = $variants;
        $this->sort     = $sort;
    }

    /**
     * Является ли тип строковым.
     */
    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    /**
     * Является ли тип целочисленным.
     */
    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    /**
     * Является ли тип вещественным.
     */
    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    /**
     * Имеет ли характеристика список допустимых значений (select).
     */
    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_characteristics}}';
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind(): void
    {
        $this->variants = array_filter((array) Json::decode($this->getAttribute('variants_json')));
        parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode(array_filter($this->variants)));
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'       => 'ID',
            'name'     => 'Название',
            'type'     => 'Тип',
            'required' => 'Обязательная',
            'default'  => 'По умолчанию',
            'sort'     => 'Сортировка',
        ];
    }
}
