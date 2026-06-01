<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100000_create_shop_brands_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_brands}}';

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
            'id'          => $this->primaryKey()->comment('PK'),
            'name'        => $this->string(255)->notNull()->comment('Название бренда'),
            'slug'        => $this->string(255)->notNull()->comment('Slug бренда'),
            'description' => $this->text()->null()->comment('Описание бренда'),
            'logo'        => $this->string(255)->null()->comment('Логотип бренда (имя файла)'),
            'sort'        => $this->integer(10)->notNull()->defaultValue(0)->comment('Порядок сортировки'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Бренды товаров');

        $this->createIndexes(static::TABLE_NAME, 'slug', false, true);
        $this->createIndexes(static::TABLE_NAME, 'sort');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
