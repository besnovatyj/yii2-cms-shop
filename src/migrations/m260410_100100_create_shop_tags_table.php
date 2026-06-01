<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100100_create_shop_tags_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_tags}}';

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
            'id'   => $this->primaryKey()->comment('PK'),
            'name' => $this->string(255)->notNull()->comment('Название тега'),
            'slug' => $this->string(255)->notNull()->comment('Slug тега'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Теги товаров');

        $this->createIndexes(static::TABLE_NAME, 'slug', false, true);

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
