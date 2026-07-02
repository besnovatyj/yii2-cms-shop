<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100400_create_shop_photos_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_photos}}';

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
            'file'       => $this->string(255)->notNull()->comment('Имя файла'),
            'sort'       => $this->integer(10)->notNull()->defaultValue(0)->comment('Сортировка'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Фотографии товаров');

        $this->createIndexes(static::TABLE_NAME, 'product_id');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
