<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\frontend;

use Besnovatyj\Forms\BaseForm;
use yii\base\Model;

/**
 * Форма добавления отзыва к товару.
 */
class ReviewForm extends BaseForm
{
    public ?int    $vote = null;
    public ?string $text = null;

    public function rules(): array
    {
        return [
            [['vote', 'text'], 'required'],
            ['vote', 'in', 'range' => array_keys($this->votesList())],
            ['text', 'string'],
        ];
    }

    /**
     * Возвращает список допустимых оценок.
     *
     * @return array<int, int>
     */
    public function votesList(): array
    {
        return [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5];
    }

    public function attributeLabels(): array
    {
        return [
            'vote' => 'Оценка',
            'text' => 'Текст отзыва',
        ];
    }
}
