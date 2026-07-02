<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101000_create_shop_tag_assignments_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_tag_assignments}}';

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
            'tag_id'     => $this->integer(10)->notNull()->comment('ID тега'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Привязка товаров к тегам (M2M)');

        $this->addPrimaryKey('pk_shop_tag_asgmt', static::TABLE_NAME, ['product_id', 'tag_id']);
        $this->createIndexes(static::TABLE_NAME, 'tag_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
