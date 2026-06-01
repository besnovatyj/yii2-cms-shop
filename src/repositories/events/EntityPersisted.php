<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories\events;

use yii\db\ActiveRecord;

/**
 * Событие: сущность сохранена (создана или обновлена).
 */
class EntityPersisted
{
    public function __construct(
        public readonly ActiveRecord $entity,
    ) {}
}
