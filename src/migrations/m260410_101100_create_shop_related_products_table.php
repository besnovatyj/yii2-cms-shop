<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101100_create_shop_related_products_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_related_products}}';

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
            'product_id' => $this->integer(10)->notNull()->comment('ID товара'),
            'related_id' => $this->integer(10)->notNull()->comment('ID связанного товара'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Связанные товары (M2M)');

        $this->addPrimaryKey('pk_shop_related', static::TABLE_NAME, ['product_id', 'related_id']);
        $this->createIndexes(static::TABLE_NAME, 'related_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
