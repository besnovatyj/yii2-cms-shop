<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100600_create_shop_values_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_values}}';

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
            'id'                => $this->primaryKey()->comment('PK'),
            'product_id'        => $this->integer(10)->notNull()->comment('ID товара'),
            'characteristic_id' => $this->integer(10)->notNull()->comment('ID характеристики'),
            'value'             => $this->string(255)->notNull()->comment('Значение характеристики'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Значения характеристик товаров (EAV)');

        $this->createIndexes(static::TABLE_NAME, 'product_id');
        $this->createIndexes(static::TABLE_NAME, 'characteristic_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
