<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop;

use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\Shop\listeners\CategoryPersistenceListener;
use Besnovatyj\Shop\repositories\events\EntityPersisted;
use Besnovatyj\DomainEvents\dispatchers\SimpleEventDispatcher;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Bootstrap модуля Shop.
 *
 * Регистрирует слушателей событий и URL-правила при старте приложения.
 * Инвалидация кеша категорий выполняется через AR-события, т.к. категории
 * управляются через TreeManager (а не CategoryRepository).
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app): void
    {
        // ── Слушатели событий ────────────────────────────────────────────────

        /** @var SimpleEventDispatcher $dispatcher */
        $dispatcher = Yii::$container->get(SimpleEventDispatcher::class);
        $dispatcher->listen(EntityPersisted::class, CategoryPersistenceListener::class);

        // Диспетчеризация события EntityPersisted при сохранении категорий через AR.
        // TreeManager использует NestedSetsRepository (AR), поэтому слушаем AR-событие напрямую.
        Event::on(Category::class, ActiveRecord::EVENT_AFTER_INSERT, function ($event) use ($dispatcher): void {
            $dispatcher->dispatch(new EntityPersisted($event->sender));
        });
        Event::on(Category::class, ActiveRecord::EVENT_AFTER_UPDATE, function ($event) use ($dispatcher): void {
            $dispatcher->dispatch(new EntityPersisted($event->sender));
        });

        // ── URL-правила ───────────────────────────────────────────────────────
        // TODO: добавить URL-правила для ЧПУ каталога (слаги категорий/брендов)
        // $app->urlManager->addRules([
        //     new CategoryUrlRule(),
        // ], false);
    }
}
