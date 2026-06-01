<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend;

use Besnovatyj\Shop\entities\Tag;
use yii\base\Model;
use yii\helpers\Inflector;

/**
 * Форма создания/редактирования тега.
 */
class TagForm extends Model
{
    public string $name = '';
    public string $slug = '';

    public function __construct(?Tag $tag = null, $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
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
            'name' => 'Название',
            'slug' => 'Slug',
        ];
    }
}
