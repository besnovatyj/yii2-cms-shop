<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\forms\backend\search;

use Besnovatyj\Shop\entities\product\Characteristic;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма поиска характеристик.
 */
class CharacteristicSearch extends Model
{
    public ?int    $id       = null;
    public ?string $name     = null;
    public ?int    $type     = null;
    public ?int    $required = null;

    public function rules(): array
    {
        return [
            [['id', 'type', 'required'], 'integer'],
            ['name', 'safe'],
        ];
    }

    /**
     * Выполняет поиск и возвращает провайдер данных.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Characteristic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['sort' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'       => $this->id,
            'type'     => $this->type,
            'required' => $this->required,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Возвращает список типов характеристик.
     *
     * @return array<int, string>
     */
    public function typesList(): array
    {
        return [
            Characteristic::TYPE_STRING  => 'Строка',
            Characteristic::TYPE_INTEGER => 'Целое число',
            Characteristic::TYPE_FLOAT   => 'Дробное число',
        ];
    }

    /**
     * Возвращает список значений «обязательно».
     *
     * @return array<int, string>
     */
    public function requiredList(): array
    {
        return [
            1 => \Yii::$app->formatter->asBoolean(true),
            0 => \Yii::$app->formatter->asBoolean(false),
        ];
    }
}
