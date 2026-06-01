<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\order\CustomerData;
use Besnovatyj\Shop\entities\order\DeliveryData;
use Besnovatyj\Shop\forms\backend\order\OrderEditForm;
use Besnovatyj\Shop\repositories\DeliveryMethodRepository;
use Besnovatyj\Shop\repositories\OrderRepository;
use Throwable;

/**
 * Сервис управления заказами (бэкэнд).
 */
class OrderManageService
{
    public function __construct(
        private readonly OrderRepository          $orders,
        private readonly DeliveryMethodRepository $deliveryMethods,
    ) {}

    /**
     * Редактирует заказ.
     */
    public function edit(int $id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);

        $order->edit(
            new CustomerData($form->customer->phone, $form->customer->name),
            $form->note,
        );

        $order->setDeliveryInfo(
            $this->deliveryMethods->get($form->delivery->method),
            new DeliveryData($form->delivery->index, $form->delivery->address),
        );

        $this->orders->save($order);
    }

    /**
     * Меняет статус заказа.
     */
    public function changeStatus(int $id, string $status): void
    {
        $order = $this->orders->get($id);

        match ($status) {
            'pay'      => $order->pay('manual'),
            'send'     => $order->send(),
            'complete' => $order->complete(),
            'cancel'   => $order->cancel('Отменён администратором'),
            default    => throw new \DomainException("Неизвестный статус: {$status}"),
        };

        $this->orders->save($order);
    }

    /**
     * Удаляет заказ.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }
}
