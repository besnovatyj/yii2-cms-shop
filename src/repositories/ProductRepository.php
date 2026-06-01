<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\product\Product;
use RuntimeException;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * Репозиторий товаров (write-side).
 */
class ProductRepository
{
    /**
     * Возвращает товар по ID или бросает исключение.
     *
     * @throws NotFoundException
     */
    public function get(int $id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new NotFoundException('Товар не найден.');
        }
        return $product;
    }

    /**
     * Проверяет существование товаров в данной основной категории.
     */
    public function existsByMainCategory(int $categoryId): bool
    {
        return Product::find()->andWhere(['category_id' => $categoryId])->exists();
    }

    /**
     * Проверяет существование товаров данного бренда.
     */
    public function existsByBrand(int $brandId): bool
    {
        return Product::find()->andWhere(['brand_id' => $brandId])->exists();
    }

    /**
     * Сохраняет товар с retry-логикой на дедлоки.
     *
     * @throws Exception
     * @throws RuntimeException
     */
    public function save(Product $product): void
    {
        $maxRetries = 3;
        $retries    = 0;

        while ($retries < $maxRetries) {
            try {
                if (!$product->save()) {
                    throw new RuntimeException('Ошибка сохранения товара.');
                }
                return;
            } catch (Exception $e) {
                // 1213 — код ошибки дедлока MySQL
                if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1213) {
                    $retries++;
                    if ($retries >= $maxRetries) {
                        throw $e;
                    }
                    usleep(random_int(100, 500) * 1000);
                    continue;
                }
                throw $e;
            }
        }
    }

    /**
     * Удаляет товар.
     *
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(Product $product): void
    {
        if (!$product->delete()) {
            throw new RuntimeException('Ошибка удаления товара.');
        }
    }
}
