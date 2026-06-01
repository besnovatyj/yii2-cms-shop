<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

use Besnovatyj\Meta\Meta;
use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\cart\cost\calculator\DynamicCost;
use Besnovatyj\Shop\cart\cost\calculator\SimpleCost;
use Besnovatyj\Shop\cart\storage\HybridStorage;
use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\Shop\repositories\ProductRepository;
use Besnovatyj\TreeManager\Manager\entities\Node;
use Besnovatyj\TreeManager\Manager\forms\TreeNodeFormInterface;
use Besnovatyj\TreeManager\Manager\TreeManager;
use Besnovatyj\TreeManager\Manager\TreeQueryScope;

/**
 * Конфигурация DI контейнера для модуля Shop.
 */
return function (\yii\di\Container $container): void {

    // ── Дерево категорий ────────────────────────────────────────────────────

    $container->setSingleton('shop.tree.manager', function () use ($container) {
        $productsRepo = new ProductRepository();

        return new TreeManager(
            modelClass: Category::class,
            entityFactory: function (TreeNodeFormInterface $form): Category {
                return Category::create(
                    $form->name,
                    $form->slug,
                    $form->description,
                    new Meta(
                        $form->meta->title,
                        $form->meta->description,
                        $form->meta->keywords,
                    ),
                );
            },
            entityUpdater: function (Node $node, TreeNodeFormInterface $form): Node {
                /** @var Category $node */
                $node->edit(
                    $form->name,
                    $form->slug,
                    $form->description,
                    new Meta(
                        $form->meta->title,
                        $form->meta->description,
                        $form->meta->keywords,
                    ),
                );
                return $node;
            },
            deleteGuard: function (Node $node) use ($productsRepo): void {
                /** @var Category $node */
                if ($productsRepo->existsByMainCategory($node->id)) {
                    throw new DomainException('Нельзя удалить категорию, к которой привязаны товары.');
                }
            },
        );
    });

    $container->setSingleton('shop.tree.scope', function (): TreeQueryScope {
        return new TreeQueryScope(Category::class);
    });

    // ── Корзина ─────────────────────────────────────────────────────────────

    $container->setSingleton(Cart::class, function (): Cart {
        $user    = \Yii::$app->user;
        $storage = new HybridStorage(
            $user,
            'shop_cart',
            60 * 60 * 24 * 30, // 30 дней
            \Yii::$app->db,
        );
        return new Cart($storage, new DynamicCost(new SimpleCost()));
    });
};
