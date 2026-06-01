<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100500_create_shop_characteristics_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_characteristics}}';

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
            'id'            => $this->primaryKey()->comment('PK'),
            'name'          => $this->string(255)->notNull()->comment('Название характеристики'),
            'type'          => $this->string(20)->notNull()->comment('Тип: string|integer|float'),
            'required'      => $this->boolean()->notNull()->defaultValue(false)->comment('Обязательная характеристика'),
            'default'       => $this->string(255)->null()->comment('Значение по умолчанию'),
            'variants_json' => $this->text()->null()->comment('JSON массив предустановленных вариантов'),
            'sort'          => $this->integer(10)->notNull()->defaultValue(0)->comment('Порядок сортировки'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Характеристики товаров');

        $this->createIndexes(static::TABLE_NAME, 'sort');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
