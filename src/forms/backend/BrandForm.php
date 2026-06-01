<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend;

use Besnovatyj\Shop\entities\Brand;
use yii\base\Model;
use yii\helpers\Inflector;

/**
 * Форма создания/редактирования бренда.
 */
class BrandForm extends Model
{
    public string $name        = '';
    public string $slug        = '';
    public ?string $description = null;
    public ?string $logo        = null;
    public int    $sort        = 0;

    public function __construct(?Brand $brand = null, $config = [])
    {
        if ($brand) {
            $this->name        = $brand->name;
            $this->slug        = $brand->slug;
            $this->description = $brand->description;
            $this->logo        = $brand->logo;
            $this->sort        = $brand->sort;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'slug', 'logo'], 'string', 'max' => 255],
            ['description', 'string'],
            ['sort', 'integer'],
            [
                'slug',
                'default',
                'value' => fn() => Inflector::slug($this->name),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name'        => 'Название',
            'slug'        => 'Slug',
            'description' => 'Описание',
            'logo'        => 'Логотип',
            'sort'        => 'Сортировка',
        ];
    }
}
