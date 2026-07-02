<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101400_create_shop_orders_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_orders}}';

    /**
     * @throws NotSupportedException
     */
    public function safeUp(): void
    {
        parent::safeUp();

        if ($this->existTable(static::TABLE_NAME)) {
            return;
        }

        $this->createTable(static::TABLE_NAME, [
            'id'                   => $this->primaryKey()->comment('PK'),
            'user_id'              => $this->integer(10)->null()->comment('ID пользователя (null = гость)'),
            'delivery_method_id'   => $this->integer(10)->null()->comment('ID метода доставки'),
            'delivery_method_name' => $this->string(255)->null()->comment('Название метода доставки (snapshot)'),
            'delivery_cost'        => $this->integer(10)->notNull()->defaultValue(0)->comment('Стоимость доставки (snapshot)'),
            'delivery_index'       => $this->string(50)->null()->comment('Почтовый индекс'),
            'delivery_address'     => $this->text()->null()->comment('Адрес доставки'),
            'customer_phone'       => $this->string(50)->notNull()->comment('Телефон покупателя'),
            'customer_name'        => $this->string(255)->notNull()->comment('Имя покупателя'),
            'payment_method'       => $this->string(50)->null()->comment('Метод оплаты'),
            'cost'                 => $this->integer(10)->notNull()->defaultValue(0)->comment('Стоимость товаров'),
            'note'                 => $this->text()->null()->comment('Примечание к заказу'),
            'current_status'       => $this->smallInteger(2)->notNull()->comment('Текущий статус'),
            'statuses_json'        => $this->text()->notNull()->comment('История статусов (JSON)'),
            'cancel_reason'        => $this->text()->null()->comment('Причина отмены'),
            'created_at'           => $this->integer(10)->notNull()->comment('Дата создания (unix)'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Заказы');

        $this->createIndexes(static::TABLE_NAME, 'user_id');
        $this->createIndexes(static::TABLE_NAME, 'current_status');
        $this->createIndexes(static::TABLE_NAME, 'created_at');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
