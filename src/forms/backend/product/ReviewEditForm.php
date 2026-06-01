<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\product;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Shop\entities\product\Review;
use yii\base\Model;

/**
 * Форма редактирования отзыва (бэкэнд).
 */
class ReviewEditForm extends BaseForm
{
    public int    $vote = 5;
    public string $text = '';

    public function __construct(?Review $review = null, $config = [])
    {
        if ($review) {
            $this->vote = $review->vote;
            $this->text = $review->text;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['vote', 'text'], 'required'],
            ['vote', 'integer', 'min' => 1, 'max' => 5],
            ['text', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'vote' => 'Оценка',
            'text' => 'Текст отзыва',
        ];
    }
}
