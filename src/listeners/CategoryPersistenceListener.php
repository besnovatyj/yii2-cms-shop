<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\listeners;

use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\Shop\repositories\events\EntityPersisted;
use yii\caching\Cache;
use yii\caching\TagDependency;

/**
 * Инвалидирует кеш дерева категорий при изменении любой категории.
 */
class CategoryPersistenceListener
{
    private Cache $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function handle(EntityPersisted $event): void
    {
        if ($event->entity instanceof Category) {
            TagDependency::invalidate($this->cache, ['shop_cat']);
        }
    }
}
