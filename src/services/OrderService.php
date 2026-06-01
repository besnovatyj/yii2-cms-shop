<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services;

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\cart\CartItem;
use Besnovatyj\Shop\entities\order\CustomerData;
use Besnovatyj\Shop\entities\order\DeliveryData;
use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\entities\order\OrderItem;
use Besnovatyj\Shop\forms\frontend\order\OrderForm;
use Besnovatyj\Shop\repositories\DeliveryMethodRepository;
use Besnovatyj\Shop\repositories\OrderRepository;
use Besnovatyj\Shop\repositories\ProductRepository;
use Throwable;
use Yii;

/**
 * Сервис оформления заказа.
 */
class OrderService
{
    public function __construct(
        private readonly Cart                     $cart,
        private readonly OrderRepository          $orders,
        private readonly ProductRepository        $products,
        private readonly DeliveryMethodRepository $deliveryMethods,
    ) {}

    /**
     * Оформляет заказ из текущего состояния корзины.
     *
     * Уменьшает остатки товаров, создаёт заказ, очищает корзину.
     *
     * @param int|null  $userId  ID авторизованного пользователя или null для гостя.
     * @param OrderForm $form    Форма оформления заказа.
     * @throws Throwable
     */
    public function checkout(?int $userId, OrderForm $form): Order
    {
        $items    = $this->cart->getItems();
        $products = [];

        // Формируем позиции заказа и уменьшаем остатки
        $orderItems = array_map(function (CartItem $item) use (&$products): OrderItem {
            $product = $item->getProduct();
            $product->checkout($item->getModificationId(), $item->getQuantity());
            $products[] = $product;

            return OrderItem::create(
                $product,
                $item->getModificationId(),
                $item->getPrice(),
                $item->getQuantity(),
            );
        }, $items);

        $order = Order::create(
            $userId,
            new CustomerData($form->customer->phone, $form->customer->name),
            $orderItems,
            (int) $this->cart->getCost()->getTotal(),
            $form->note,
        );

        $deliveryMethod = $this->deliveryMethods->get($form->delivery->method);
        $order->setDeliveryInfo(
            $deliveryMethod,
            new DeliveryData($form->delivery->index, $form->delivery->address),
        );

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->orders->save($order);

            // Сохраняем позиции заказа
            foreach ($orderItems as $orderItem) {
                $orderItem->order_id = $order->id;
                $orderItem->save();
            }

            // Сохраняем обновлённые остатки товаров
            foreach ($products as $product) {
                $this->products->save($product);
            }

            $this->cart->clear();
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $order;
    }
}
