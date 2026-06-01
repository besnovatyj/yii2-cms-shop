<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\product\Characteristic;
use Besnovatyj\Shop\forms\backend\CharacteristicForm;
use Besnovatyj\Shop\repositories\CharacteristicRepository;
use Throwable;

/**
 * Сервис управления характеристиками.
 */
class CharacteristicManageService
{
    public function __construct(
        private readonly CharacteristicRepository $characteristics,
    ) {}

    /**
     * Создаёт характеристику.
     */
    public function create(CharacteristicForm $form): Characteristic
    {
        $characteristic = Characteristic::create(
            $form->name,
            $form->type,
            (bool) $form->required,
            $form->default,
            $form->variants,
            $form->sort,
        );
        $this->characteristics->save($characteristic);
        return $characteristic;
    }

    /**
     * Редактирует характеристику.
     */
    public function edit(int $id, CharacteristicForm $form): void
    {
        $characteristic = $this->characteristics->get($id);
        $characteristic->edit(
            $form->name,
            $form->type,
            (bool) $form->required,
            $form->default,
            $form->variants,
            $form->sort,
        );
        $this->characteristics->save($characteristic);
    }

    /**
     * Удаляет характеристику.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $characteristic = $this->characteristics->get($id);
        $this->characteristics->remove($characteristic);
    }
}
