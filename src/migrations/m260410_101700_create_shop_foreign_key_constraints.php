<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use Yii;
use yii\db\Exception;

/**
 * Миграция внешних ключей модуля Shop.
 *
 * Все FK создаются одной миграцией в конце, чтобы избежать проблем с порядком
 * установки таблиц. safeDown переопределён пустым методом, так как BaseMigration::safeDown()
 * вызывает static::TABLE_NAME которого здесь нет. Удаление FK произойдёт автоматически
 * при удалении таблиц при переустановке модуля.
 */
class m260410_101700_create_shop_foreign_key_constraints extends BaseMigration
{
    /**
     * @throws Exception
     */
    public function safeUp(): void
    {
        parent::safeUp();

        Yii::$app->getDb()->createCommand('SET foreign_key_checks = 0')->execute();

        // Фото → товар
        $this->createFKs(
            m260410_100400_create_shop_photos_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Значения характеристик → товар
        $this->createFKs(
            m260410_100600_create_shop_values_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );
        // Значения характеристик → характеристика
        $this->createFKs(
            m260410_100600_create_shop_values_table::TABLE_NAME,
            'characteristic_id',
            m260410_100500_create_shop_characteristics_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Модификации → товар
        $this->createFKs(
            m260410_100700_create_shop_modifications_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Отзывы → товар
        $this->createFKs(
            m260410_100800_create_shop_reviews_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Привязки категорий → товар
        $this->createFKs(
            m260410_100900_create_shop_category_assignments_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );
        // Привязки категорий → категория
        $this->createFKs(
            m260410_100900_create_shop_category_assignments_table::TABLE_NAME,
            'category_id',
            m260410_100200_create_shop_categories_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Привязки тегов → товар
        $this->createFKs(
            m260410_101000_create_shop_tag_assignments_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );
        // Привязки тегов → тег
        $this->createFKs(
            m260410_101000_create_shop_tag_assignments_table::TABLE_NAME,
            'tag_id',
            m260410_100100_create_shop_tags_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Связанные товары → товар (source)
        $this->createFKs(
            m260410_101100_create_shop_related_products_table::TABLE_NAME,
            'product_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );
        // Связанные товары → товар (target)
        $this->createFKs(
            m260410_101100_create_shop_related_products_table::TABLE_NAME,
            'related_id',
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Товары → бренд
        $this->createFKs(
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'brand_id',
            m260410_100000_create_shop_brands_table::TABLE_NAME,
            'id',
            'SET NULL',
        );
        // Товары → основная категория
        $this->createFKs(
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'category_id',
            m260410_100200_create_shop_categories_table::TABLE_NAME,
            'id',
            'SET NULL',
        );
        // Товары → основное фото
        $this->createFKs(
            m260410_100300_create_shop_products_table::TABLE_NAME,
            'main_photo_id',
            m260410_100400_create_shop_photos_table::TABLE_NAME,
            'id',
            'SET NULL',
        );

        // Позиции заказа → заказ
        $this->createFKs(
            m260410_101500_create_shop_order_items_table::TABLE_NAME,
            'order_id',
            m260410_101400_create_shop_orders_table::TABLE_NAME,
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Заказы → метод доставки
        $this->createFKs(
            m260410_101400_create_shop_orders_table::TABLE_NAME,
            'delivery_method_id',
            m260410_101200_create_shop_delivery_methods_table::TABLE_NAME,
            'id',
            'SET NULL',
        );

        Yii::$app->getDb()->createCommand('SET foreign_key_checks = 1')->execute();
    }

    /**
     * Переопределяем пустым методом, так как BaseMigration::safeDown() вызывает static::TABLE_NAME,
     * которого в данной миграции нет. Удаление FK произойдёт автоматически при дропе таблиц
     * при переустановке модуля.
     */
    public function safeDown(): void
    {
        // empty
    }
}
