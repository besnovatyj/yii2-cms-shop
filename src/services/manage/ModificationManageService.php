<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\product\Modification;
use Besnovatyj\Shop\forms\backend\product\ModificationForm;
use Besnovatyj\Shop\repositories\ProductRepository;
use DomainException;
use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Сервис управления модификациями товара.
 */
class ModificationManageService
{
    public function __construct(
        private readonly ProductRepository $products,
    ) {}

    /**
     * Добавляет новую модификацию к товару.
     *
     * @throws DomainException Если модификация с таким кодом уже существует.
     * @throws RuntimeException
     */
    public function add(int $productId, ModificationForm $form): Modification
    {
        $product = $this->products->get($productId);

        // Проверяем уникальность кода в рамках товара
        $exists = Modification::find()
            ->andWhere(['product_id' => $productId, 'code' => $form->code])
            ->exists();

        if ($exists) {
            throw new DomainException('Модификация с таким артикулом уже существует.');
        }

        $modification = Modification::create($productId, $form->code, $form->name, $form->price, $form->quantity);

        if (!$modification->save()) {
            throw new RuntimeException('Ошибка сохранения модификации.');
        }

        // Пересчитываем суммарный остаток товара
        $this->updateProductQuantity($productId);

        return $modification;
    }

    /**
     * Редактирует модификацию.
     *
     * @throws RuntimeException
     */
    public function edit(int $productId, int $modificationId, ModificationForm $form): void
    {
        $modification = $this->getModification($productId, $modificationId);
        $modification->edit($form->code, $form->name, $form->price, $form->quantity);

        if (!$modification->save()) {
            throw new RuntimeException('Ошибка сохранения модификации.');
        }

        $this->updateProductQuantity($productId);
    }

    /**
     * Удаляет модификацию.
     *
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(int $productId, int $modificationId): void
    {
        $modification = $this->getModification($productId, $modificationId);

        if (!$modification->delete()) {
            throw new RuntimeException('Ошибка удаления модификации.');
        }

        $this->updateProductQuantity($productId);
    }

    // ── Private ───────────────────────────────────────────────────────────

    /**
     * Возвращает модификацию, проверяя принадлежность товару.
     *
     * @throws \Besnovatyj\Shop\repositories\NotFoundException
     * @throws DomainException
     */
    private function getModification(int $productId, int $modificationId): Modification
    {
        $modification = Modification::find()
            ->andWhere(['id' => $modificationId, 'product_id' => $productId])
            ->one();

        if (!$modification) {
            throw new DomainException('Модификация не найдена.');
        }

        return $modification;
    }

    /**
     * Пересчитывает и сохраняет суммарный остаток товара.
     */
    private function updateProductQuantity(int $productId): void
    {
        $total = (int) Modification::find()
            ->select('SUM(quantity)')
            ->andWhere(['product_id' => $productId])
            ->scalar();

        $product           = $this->products->get($productId);
        $product->quantity = $total;
        $this->products->save($product);
    }
}
