<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100900_create_shop_category_assignments_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_category_assignments}}';

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
            'product_id'  => $this->integer(10)->notNull()->comment('ID товара'),
            'category_id' => $this->integer(10)->notNull()->comment('ID категории'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Привязка товаров к категориям (M2M)');

        $this->addPrimaryKey('pk_shop_cat_asgmt', static::TABLE_NAME, ['product_id', 'category_id']);
        $this->createIndexes(static::TABLE_NAME, 'category_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
