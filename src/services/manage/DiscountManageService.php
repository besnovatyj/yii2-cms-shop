<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\Discount;
use Besnovatyj\Shop\forms\backend\DiscountForm;
use Besnovatyj\Shop\repositories\DiscountRepository;
use Throwable;

/**
 * Сервис управления скидками.
 */
class DiscountManageService
{
    public function __construct(
        private readonly DiscountRepository $discounts,
    ) {}

    /**
     * Создаёт скидку.
     */
    public function create(DiscountForm $form): Discount
    {
        $discount = Discount::create($form->percent, $form->name, $form->fromDate, $form->toDate, $form->sort);
        $this->discounts->save($discount);
        return $discount;
    }

    /**
     * Редактирует скидку.
     */
    public function edit(int $id, DiscountForm $form): void
    {
        $discount = $this->discounts->get($id);
        $discount->edit($form->percent, $form->name, $form->fromDate, $form->toDate, $form->sort);
        $this->discounts->save($discount);
    }

    /**
     * Активирует скидку.
     */
    public function activate(int $id): void
    {
        $discount = $this->discounts->get($id);
        $discount->activate();
        $this->discounts->save($discount);
    }

    /**
     * Деактивирует скидку.
     */
    public function deactivate(int $id): void
    {
        $discount = $this->discounts->get($id);
        $discount->deactivate();
        $this->discounts->save($discount);
    }

    /**
     * Удаляет скидку.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $discount = $this->discounts->get($id);
        $this->discounts->remove($discount);
    }
}
