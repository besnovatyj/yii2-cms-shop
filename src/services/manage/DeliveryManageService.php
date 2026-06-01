<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\DeliveryMethod;
use Besnovatyj\Shop\forms\backend\DeliveryMethodForm;
use Besnovatyj\Shop\repositories\DeliveryMethodRepository;
use Throwable;

/**
 * Сервис управления способами доставки.
 */
class DeliveryManageService
{
    public function __construct(
        private readonly DeliveryMethodRepository $deliveryMethods,
    ) {}

    /**
     * Создаёт способ доставки.
     */
    public function create(DeliveryMethodForm $form): DeliveryMethod
    {
        $method = DeliveryMethod::create(
            $form->name,
            $form->cost,
            $form->minWeight,
            $form->maxWeight,
            $form->sort,
        );
        $this->deliveryMethods->save($method);
        return $method;
    }

    /**
     * Редактирует способ доставки.
     */
    public function edit(int $id, DeliveryMethodForm $form): void
    {
        $method = $this->deliveryMethods->get($id);
        $method->edit(
            $form->name,
            $form->cost,
            $form->minWeight,
            $form->maxWeight,
            $form->sort,
        );
        $this->deliveryMethods->save($method);
    }

    /**
     * Активирует способ доставки.
     */
    public function activate(int $id): void
    {
        $method = $this->deliveryMethods->get($id);
        $method->activate();
        $this->deliveryMethods->save($method);
    }

    /**
     * Деактивирует способ доставки.
     */
    public function deactivate(int $id): void
    {
        $method = $this->deliveryMethods->get($id);
        $method->deactivate();
        $this->deliveryMethods->save($method);
    }

    /**
     * Удаляет способ доставки.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $method = $this->deliveryMethods->get($id);
        $this->deliveryMethods->remove($method);
    }
}
