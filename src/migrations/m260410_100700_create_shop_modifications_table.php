<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100700_create_shop_modifications_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_modifications}}';

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
            'id'         => $this->primaryKey()->comment('PK'),
            'product_id' => $this->integer(10)->notNull()->comment('ID товара'),
            'code'       => $this->string(255)->notNull()->comment('Артикул модификации'),
            'name'       => $this->string(255)->notNull()->comment('Название модификации'),
            'price'      => $this->integer(10)->notNull()->defaultValue(0)->comment('Цена (0 = использовать цену товара)'),
            'quantity'   => $this->integer(10)->notNull()->defaultValue(0)->comment('Остаток'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Модификации (варианты) товаров');

        $this->createIndexes(static::TABLE_NAME, 'product_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
