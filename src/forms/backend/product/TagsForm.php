<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Shop\entities\product\Product;
use yii\base\Model;

/**
 * Форма тегов товара.
 *
 * Позволяет задать теги через запятую (строкой).
 * После валидации доступен массив newTagsNames.
 */
class TagsForm extends Model
{
    /** @var string Теги через запятую */
    public string $tagsString = '';

    /** @var string[] Имена тегов после разбора */
    public array $newTagsNames = [];

    public function __construct(?Product $product = null, $config = [])
    {
        if ($product) {
            $names = array_map(fn($tag) => $tag->name, $product->tags);
            $this->tagsString   = implode(', ', $names);
            $this->newTagsNames = $names;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['tagsString', 'string'],
        ];
    }

    public function afterValidate(): void
    {
        parent::afterValidate();
        $this->newTagsNames = array_values(array_filter(
            array_map('trim', explode(',', $this->tagsString))
        ));
    }

    public function attributeLabels(): array
    {
        return [
            'tagsString' => 'Теги (через запятую)',
        ];
    }
}
