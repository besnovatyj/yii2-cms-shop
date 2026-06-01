<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use common\components\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100800_create_shop_reviews_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_reviews}}';

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
            'user_id'    => $this->integer(10)->null()->comment('ID пользователя (null = анонимный)'),
            'vote'       => $this->smallInteger(1)->notNull()->comment('Оценка 1–5'),
            'text'       => $this->text()->notNull()->comment('Текст отзыва'),
            'status'     => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Статус: 0=черновик, 1=активен'),
            'created_at' => $this->integer(10)->notNull()->comment('Дата создания (unix)'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Отзывы на товары');

        $this->createIndexes(static::TABLE_NAME, 'product_id');
        $this->createIndexes(static::TABLE_NAME, 'status');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
