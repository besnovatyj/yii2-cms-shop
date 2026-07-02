<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_101300_create_shop_discounts_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_discounts}}';

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
            'id'        => $this->primaryKey()->comment('PK'),
            'name'      => $this->string(255)->notNull()->comment('Название скидки'),
            'percent'   => $this->integer(3)->notNull()->defaultValue(0)->comment('Размер скидки в процентах (0–100)'),
            'from_date' => $this->date()->null()->comment('Дата начала действия'),
            'to_date'   => $this->date()->null()->comment('Дата окончания действия'),
            'active'    => $this->boolean()->notNull()->defaultValue(false)->comment('Активна'),
            'sort'      => $this->integer(10)->notNull()->defaultValue(0)->comment('Порядок сортировки'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Скидки');

        $this->createIndexes(static::TABLE_NAME, 'active');
        $this->createIndexes(static::TABLE_NAME, 'sort');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
