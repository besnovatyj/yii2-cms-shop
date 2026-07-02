<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260410_100300_create_shop_products_table extends BaseMigration
{
    public const string TABLE_NAME = '{{%shop_products}}';

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
            'brand_id'      => $this->integer(10)->null()->comment('ID бренда'),
            'category_id'   => $this->integer(10)->null()->comment('ID основной категории'),
            'main_photo_id' => $this->integer(10)->null()->comment('ID основного фото'),
            'code'          => $this->string(255)->notNull()->defaultValue('')->comment('Артикул'),
            'name'          => $this->string(255)->notNull()->comment('Название'),
            'description'   => $this->text()->null()->comment('Описание'),
            'weight'        => $this->integer(10)->notNull()->defaultValue(0)->comment('Масса (г)'),
            'quantity'      => $this->integer(10)->notNull()->defaultValue(0)->comment('Остаток'),
            'price_new'     => $this->integer(10)->notNull()->defaultValue(0)->comment('Актуальная цена (коп/руб)'),
            'price_old'     => $this->integer(10)->notNull()->defaultValue(0)->comment('Старая цена (коп/руб)'),
            'rating'        => $this->float()->null()->comment('Средний рейтинг по отзывам'),
            'meta_json'     => $this->text()->notNull()->comment('JSON мета-данных'),
            'status'        => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Статус: 0=черновик, 1=активен'),
            'created_at'    => $this->integer(10)->notNull()->comment('Дата создания (unix)'),
        ], $this->tableOptions);
        $this->addCommentOnTable(static::TABLE_NAME, 'Товары');

        $this->createIndexes(static::TABLE_NAME, 'brand_id');
        $this->createIndexes(static::TABLE_NAME, 'category_id');
        $this->createIndexes(static::TABLE_NAME, 'status');

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
