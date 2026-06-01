<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories\events;

use yii\db\ActiveRecord;

/**
 * Событие: сущность удалена.
 */
class EntityRemoved
{
    public function __construct(
        public readonly ActiveRecord $entity,
    ) {}
}
