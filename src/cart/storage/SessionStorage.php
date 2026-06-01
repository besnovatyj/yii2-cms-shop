<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\storage;

use yii\web\Session;

/**
 * Хранилище корзины в PHP-сессии (для гостей без cookie).
 */
class SessionStorage implements StorageInterface
{
    /**
     * @param string  $key     Ключ в сессии.
     * @param Session $session Сессия Yii2.
     */
    public function __construct(
        private readonly string  $key,
        private readonly Session $session,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function load(): array
    {
        return $this->session->get($this->key, []);
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $items): void
    {
        $this->session->set($this->key, $items);
    }
}
