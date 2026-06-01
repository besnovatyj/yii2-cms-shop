<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\forms\backend\BrandForm;
use Besnovatyj\Shop\repositories\BrandRepository;
use Besnovatyj\Shop\repositories\ProductRepository;
use DomainException;
use Throwable;

/**
 * Сервис управления брендами.
 */
class BrandManageService
{
    public function __construct(
        private BrandRepository   $brands,
        private ProductRepository $products,
    ) {}

    /**
     * Создаёт бренд.
     */
    public function create(BrandForm $form): Brand
    {
        $brand = Brand::create($form->name, $form->slug, $form->description, $form->logo, $form->sort);
        $this->brands->save($brand);
        return $brand;
    }

    /**
     * Редактирует бренд.
     */
    public function edit(int $id, BrandForm $form): void
    {
        $brand = $this->brands->get($id);
        $brand->edit($form->name, $form->slug, $form->description, $form->logo, $form->sort);
        $this->brands->save($brand);
    }

    /**
     * Удаляет бренд.
     *
     * @throws DomainException Если с брендом связаны товары.
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $brand = $this->brands->get($id);
        if ($this->products->existsByBrand($brand->id)) {
            throw new DomainException('Нельзя удалить бренд, к которому привязаны товары.');
        }
        $this->brands->remove($brand);
    }
}
