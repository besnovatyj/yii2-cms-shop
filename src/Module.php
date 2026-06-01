<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop;

use common\components\module\BaseModule;

/**
 * Модуль интернет-магазина.
 */
class Module extends BaseModule
{
    public const bool EDITABLE = true;

    /**
     * {@inheritdoc}
     */
    public static function getAdminMenu(): array
    {
        return require __DIR__ . '/config/adminMenu.php';
    }

    /**
     * {@inheritdoc}
     */
    public static function getConfig(): array
    {
        return require __DIR__ . '/config/config.php';
    }

    /**
     * {@inheritdoc}
     */
    public static function getOptions(): array
    {
        return require __DIR__ . '/config/options.php';
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return require __DIR__ . '/config/dependencies.php';
    }

    /**
     * {@inheritdoc}
     */
    public static function setContainerConfig(): void
    {
        (require __DIR__ . '/config/container.php')(\Yii::$container);
    }
}
