<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101200_create_shop_delivery_methods_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_delivery_methods}}';

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
            'name'       => $this->string(255)->notNull()->comment('Название метода доставки'),
            'cost'       => $this->integer(10)->notNull()->defaultValue(0)->comment('Стоимость доставки'),
            'min_weight' => $this->integer(10)->notNull()->defaultValue(0)->comment('Минимальный вес (г)'),
            'max_weight' => $this->integer(10)->null()->comment('Максимальный вес (г), null = без ограничения'),
            'active'     => $this->boolean()->notNull()->defaultValue(true)->comment('Активен'),
            'sort'       => $this->integer(10)->notNull()->defaultValue(0)->comment('Порядок сортировки'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Методы доставки');

        $this->createIndexes(static::TABLE_NAME, 'sort');
        $this->createIndexes(static::TABLE_NAME, 'active');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
