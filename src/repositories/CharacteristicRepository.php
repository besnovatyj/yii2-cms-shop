<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\product\Characteristic;
use RuntimeException;
use Throwable;

/**
 * Репозиторий характеристик.
 */
class CharacteristicRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): Characteristic
    {
        if (!$characteristic = Characteristic::findOne($id)) {
            throw new NotFoundException('Характеристика не найдена.');
        }
        return $characteristic;
    }

    /**
     * Возвращает все характеристики, отсортированные по sort.
     *
     * @return Characteristic[]
     */
    public function findAll(): array
    {
        return Characteristic::find()->orderBy(['sort' => SORT_ASC])->all();
    }

    /**
     * @throws RuntimeException
     */
    public function save(Characteristic $characteristic): void
    {
        if (!$characteristic->save()) {
            throw new RuntimeException('Ошибка сохранения характеристики.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(Characteristic $characteristic): void
    {
        if (!$characteristic->delete()) {
            throw new RuntimeException('Ошибка удаления характеристики.');
        }
    }
}
