<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Meta\Meta;
use Besnovatyj\Shop\entities\product\CategoryAssignment;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\entities\product\RelatedAssignment;
use Besnovatyj\Shop\entities\product\TagAssignment;
use Besnovatyj\Shop\entities\product\Value;
use Besnovatyj\Shop\entities\Tag;
use Besnovatyj\Shop\forms\backend\product\CategoriesForm;
use Besnovatyj\Shop\forms\backend\product\PriceForm;
use Besnovatyj\Shop\forms\backend\product\ProductCreateForm;
use Besnovatyj\Shop\forms\backend\product\ProductEditForm;
use Besnovatyj\Shop\forms\backend\product\QuantityForm;
use Besnovatyj\Shop\forms\backend\product\TagsForm;
use Besnovatyj\Shop\repositories\BrandRepository;
use Besnovatyj\Shop\repositories\CategoryRepository;
use Besnovatyj\Shop\repositories\ProductRepository;
use Besnovatyj\Shop\repositories\TagRepository;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\helpers\Inflector;

/**
 * Сервис управления товарами (CRUD + управление связями).
 */
class ProductManageService
{
    public function __construct(
        private readonly ProductRepository  $products,
        private readonly BrandRepository    $brands,
        private readonly CategoryRepository $categories,
        private readonly TagRepository      $tags,
    ) {}

    /**
     * Создаёт новый товар.
     *
     * @throws Throwable
     */
    public function create(ProductCreateForm $form): Product
    {
        $brand    = $form->brandId ? $this->brands->get($form->brandId) : null;
        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $brand?->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            $form->quantity->quantity,
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords),
        );
        $product->setPrice($form->price->new, $form->price->old);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->products->save($product);

            // Дополнительные категории
            foreach ($form->categories->others as $otherId) {
                $this->assignCategory($product, (int) $otherId);
            }

            // Значения характеристик
            foreach ($form->values as $characteristicId => $value) {
                $this->saveValue($product->id, (int) $characteristicId, (string) $value);
            }

            // Теги
            $this->syncTags($product, $form->tags);

            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $product;
    }

    /**
     * Редактирует товар.
     *
     * @throws Throwable
     */
    public function edit(int $id, ProductEditForm $form): void
    {
        $product  = $this->products->get($id);
        $brand    = $form->brandId ? $this->brands->get($form->brandId) : null;
        $category = $this->categories->get($form->categories->main);

        if ($category->isRoot()) {
            throw new \DomainException('Нельзя назначить корневую категорию товару.');
        }

        $product->edit(
            $brand?->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            new Meta($form->meta->title, $form->meta->description, $form->meta->keywords),
        );
        $product->changeMainCategory($category->id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->products->save($product);

            // Пересинхронизируем дополнительные категории
            CategoryAssignment::deleteAll(['product_id' => $product->id]);
            foreach ($form->categories->others as $otherId) {
                $this->assignCategory($product, (int) $otherId);
            }

            // Значения характеристик
            foreach ($form->values as $characteristicId => $value) {
                $this->saveValue($product->id, (int) $characteristicId, (string) $value);
            }

            // Теги
            $this->syncTags($product, $form->tags);

            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Изменяет цену товара.
     *
     * @throws Exception
     */
    public function changePrice(int $id, PriceForm $form): void
    {
        $product = $this->products->get($id);
        $product->setPrice($form->new, $form->old);
        $this->products->save($product);
    }

    /**
     * Изменяет количество товара.
     *
     * @throws Exception
     */
    public function changeQuantity(int $id, QuantityForm $form): void
    {
        $product = $this->products->get($id);
        $product->changeQuantity($form->quantity);
        $this->products->save($product);
    }

    /**
     * Публикует товар.
     *
     * @throws Exception
     */
    public function activate(int $id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    /**
     * Переводит товар в черновик.
     *
     * @throws Exception
     */
    public function draft(int $id): void
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    /**
     * Добавляет сопутствующий товар.
     *
     * @throws Exception
     */
    public function addRelatedProduct(int $id, int $relatedId): void
    {
        $product = $this->products->get($id);
        $this->products->get($relatedId); // проверяем существование

        $exists = RelatedAssignment::find()
            ->andWhere(['product_id' => $product->id, 'related_id' => $relatedId])
            ->exists();

        if (!$exists) {
            $assignment             = new RelatedAssignment();
            $assignment->product_id = $product->id;
            $assignment->related_id = $relatedId;
            $assignment->save();
        }
    }

    /**
     * Удаляет сопутствующий товар.
     */
    public function removeRelatedProduct(int $id, int $relatedId): void
    {
        RelatedAssignment::deleteAll(['product_id' => $id, 'related_id' => $relatedId]);
    }

    /**
     * Удаляет товар.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $product = $this->products->get($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            TagAssignment::deleteAll(['product_id' => $product->id]);
            CategoryAssignment::deleteAll(['product_id' => $product->id]);
            RelatedAssignment::deleteAll(['product_id' => $product->id]);
            RelatedAssignment::deleteAll(['related_id' => $product->id]);
            Value::deleteAll(['product_id' => $product->id]);

            $this->products->remove($product);
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Создаёт привязку дополнительной категории.
     */
    private function assignCategory(Product $product, int $categoryId): void
    {
        $exists = CategoryAssignment::find()
            ->andWhere(['product_id' => $product->id, 'category_id' => $categoryId])
            ->exists();

        if (!$exists) {
            $a = CategoryAssignment::create($product->id, $categoryId);
            $a->save();
        }
    }

    /**
     * Сохраняет значение характеристики товара (upsert).
     * Пустая строка приводит к удалению записи.
     *
     * @throws Exception
     */
    private function saveValue(int $productId, int $characteristicId, string $value): void
    {
        $existing = Value::findOne(['product_id' => $productId, 'characteristic_id' => $characteristicId]);
        if ($value === '') {
            if ($existing) {
                $existing->delete();
            }
            return;
        }
        if ($existing) {
            $existing->change($value);
            $existing->save();
        } else {
            Value::create($productId, $characteristicId, $value)->save();
        }
    }

    /**
     * Синхронизирует теги товара.
     *
     * @throws Exception
     */
    private function syncTags(Product $product, TagsForm $tagsForm): void
    {
        TagAssignment::deleteAll(['product_id' => $product->id]);

        foreach ($tagsForm->newTagsNames as $tagName) {
            $slug = Inflector::slug($tagName);
            $tag  = $this->tags->findBySlug($slug);
            if (!$tag) {
                $tag = Tag::create($tagName, $slug);
                $this->tags->save($tag);
            }
            $assignment = TagAssignment::create($product->id, $tag->id);
            $assignment->save();
        }
    }
}
