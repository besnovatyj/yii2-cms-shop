<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101600_create_shop_cart_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_cart}}';

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
            'id'              => $this->primaryKey()->comment('PK'),
            'user_id'         => $this->integer(10)->notNull()->comment('ID пользователя'),
            'product_id'      => $this->integer(10)->notNull()->comment('ID товара'),
            'modification_id' => $this->integer(10)->null()->comment('ID модификации (null если без модификации)'),
            'quantity'        => $this->integer(10)->notNull()->defaultValue(1)->comment('Количество'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Корзина покупателей (DB storage)');

        $this->createIndexes(static::TABLE_NAME, 'user_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
