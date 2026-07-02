<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101500_create_shop_order_items_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_order_items}}';

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
            'id'                 => $this->primaryKey()->comment('PK'),
            'order_id'           => $this->integer(10)->notNull()->comment('ID заказа'),
            'product_id'         => $this->integer(10)->null()->comment('ID товара (может быть null если товар удалён)'),
            'modification_id'    => $this->integer(10)->null()->comment('ID модификации (null если без модификации)'),
            'product_name'       => $this->string(255)->notNull()->comment('Название товара (snapshot)'),
            'product_code'       => $this->string(255)->notNull()->comment('Артикул товара (snapshot)'),
            'modification_name'  => $this->string(255)->null()->comment('Название модификации (snapshot)'),
            'modification_code'  => $this->string(255)->null()->comment('Артикул модификации (snapshot)'),
            'price'              => $this->integer(10)->notNull()->comment('Цена на момент заказа'),
            'quantity'           => $this->integer(10)->notNull()->defaultValue(1)->comment('Количество'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Позиции заказа');

        $this->createIndexes(static::TABLE_NAME, 'order_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
