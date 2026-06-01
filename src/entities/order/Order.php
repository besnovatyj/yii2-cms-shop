<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\order;

use Besnovatyj\Shop\entities\DeliveryMethod;
use DomainException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Заказ.
 *
 * @property int          $id
 * @property int          $created_at
 * @property int|null     $user_id
 * @property int|null     $delivery_method_id
 * @property string|null  $delivery_method_name
 * @property int          $delivery_cost
 * @property string|null  $payment_method
 * @property int          $cost
 * @property string|null  $note
 * @property int          $current_status
 * @property string|null  $cancel_reason
 *
 * @property CustomerData $customerData
 * @property DeliveryData $deliveryData
 * @property Status[]     $statuses
 * @property OrderItem[]  $items
 */
class Order extends ActiveRecord
{
    public CustomerData $customerData;
    public DeliveryData $deliveryData;

    /** @var Status[] */
    public array $statuses = [];

    /**
     * Создаёт новый заказ.
     *
     * @param OrderItem[] $items
     */
    public static function create(
        ?int         $userId,
        CustomerData $customerData,
        array        $items,
        int          $cost,
        ?string      $note,
    ): self {
        $order               = new static();
        $order->user_id      = $userId;
        $order->customerData = $customerData;
        $order->deliveryData = new DeliveryData(null, '');
        $order->items        = $items;
        $order->cost         = $cost;
        $order->note         = $note;
        $order->created_at   = time();
        $order->delivery_cost = 0;
        $order->addStatus(Status::NEW);
        return $order;
    }

    /**
     * Редактирует контактные данные заказа.
     */
    public function edit(CustomerData $customerData, ?string $note): void
    {
        $this->customerData = $customerData;
        $this->note         = $note;
    }

    /**
     * Устанавливает информацию о доставке.
     */
    public function setDeliveryInfo(DeliveryMethod $method, DeliveryData $deliveryData): void
    {
        $this->delivery_method_id   = $method->id;
        $this->delivery_method_name = $method->name;
        $this->delivery_cost        = $method->cost;
        $this->deliveryData         = $deliveryData;
    }

    /**
     * Помечает заказ как оплаченный.
     *
     * @throws DomainException
     */
    public function pay(string $method): void
    {
        if ($this->isPaid()) {
            throw new DomainException('Заказ уже оплачен.');
        }
        $this->payment_method = $method;
        $this->addStatus(Status::PAID);
    }

    /**
     * Отмечает заказ как отправленный.
     *
     * @throws DomainException
     */
    public function send(): void
    {
        if ($this->isSent()) {
            throw new DomainException('Заказ уже отправлен.');
        }
        $this->addStatus(Status::SENT);
    }

    /**
     * Завершает заказ.
     *
     * @throws DomainException
     */
    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new DomainException('Заказ уже завершён.');
        }
        $this->addStatus(Status::COMPLETED);
    }

    /**
     * Отменяет заказ.
     *
     * @throws DomainException
     */
    public function cancel(string $reason): void
    {
        if ($this->isCanceled()) {
            throw new DomainException('Заказ уже отменён.');
        }
        $this->cancel_reason = $reason;
        $this->addStatus(Status::CANCELLED);
    }

    /**
     * Возвращает полную стоимость заказа (товары + доставка).
     */
    public function getTotalCost(): int
    {
        return $this->cost + $this->delivery_cost;
    }

    /** @return bool */
    public function canBePaid(): bool { return $this->isNew(); }
    /** @return bool */
    public function isNew(): bool { return $this->current_status === Status::NEW; }
    /** @return bool */
    public function isPaid(): bool { return $this->current_status === Status::PAID; }
    /** @return bool */
    public function isSent(): bool { return $this->current_status === Status::SENT; }
    /** @return bool */
    public function isCompleted(): bool { return $this->current_status === Status::COMPLETED; }
    /** @return bool */
    public function isCanceled(): bool { return in_array($this->current_status, [Status::CANCELLED, Status::CANCELLED_BY_CUSTOMER], true); }

    /**
     * Связь с позициями заказа.
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind(): void
    {
        $statuses = Json::decode($this->getAttribute('statuses_json'));
        $this->statuses = array_map(
            fn(array $row) => new Status($row['value'], $row['created_at']),
            is_array($statuses) ? $statuses : [],
        );

        $this->customerData = new CustomerData(
            (string) $this->getAttribute('customer_phone'),
            (string) $this->getAttribute('customer_name'),
        );

        $this->deliveryData = new DeliveryData(
            $this->getAttribute('delivery_index'),
            (string) $this->getAttribute('delivery_address'),
        );

        parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
        $this->setAttribute('statuses_json', Json::encode(array_map(
            fn(Status $s) => ['value' => $s->value, 'created_at' => $s->created_at],
            $this->statuses,
        )));
        $this->setAttribute('customer_phone',   $this->customerData->phone);
        $this->setAttribute('customer_name',    $this->customerData->name);
        $this->setAttribute('delivery_index',   $this->deliveryData->index);
        $this->setAttribute('delivery_address', $this->deliveryData->address);

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'             => 'ID',
            'created_at'     => 'Дата создания',
            'current_status' => 'Статус',
            'cost'           => 'Стоимость товаров',
            'delivery_cost'  => 'Стоимость доставки',
            'note'           => 'Комментарий',
        ];
    }

    /**
     * Добавляет новый статус в историю и устанавливает текущий.
     */
    private function addStatus(int $value): void
    {
        $this->statuses[]    = new Status($value, time());
        $this->current_status = $value;
    }
}
