<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\cart\storage;

use Besnovatyj\Shop\cart\CartItem;
use yii\db\Connection;
use yii\web\User;

/**
 * Гибридное хранилище корзины.
 *
 * Для гостей использует CookieStorage.
 * После авторизации переносит товары из cookie в БД (DbStorage).
 */
class HybridStorage implements StorageInterface
{
    private ?StorageInterface $storage = null;

    /**
     * @param User       $user          Компонент пользователя Yii2.
     * @param string     $cookieKey     Ключ cookie.
     * @param int        $cookieTimeout Время жизни cookie (секунды).
     * @param Connection $db            Соединение с БД.
     */
    public function __construct(
        private readonly User       $user,
        private readonly string     $cookieKey,
        private readonly int        $cookieTimeout,
        private readonly Connection $db,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function load(): array
    {
        return $this->getStorage()->load();
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $items): void
    {
        $this->getStorage()->save($items);
    }

    /**
     * Возвращает активное хранилище с учётом статуса авторизации.
     * При необходимости выполняет перенос корзины из cookie в БД.
     */
    private function getStorage(): StorageInterface
    {
        if ($this->storage !== null) {
            return $this->storage;
        }

        $cookieStorage = new CookieStorage($this->cookieKey, $this->cookieTimeout);

        if ($this->user->isGuest) {
            $this->storage = $cookieStorage;
            return $this->storage;
        }

        // Авторизованный пользователь — используем БД.
        // Если в cookie были товары — переносим их и очищаем cookie.
        $dbStorage = new DbStorage((int) $this->user->id, $this->db);

        $cookieItems = $cookieStorage->load();
        if ($cookieItems) {
            $dbItems = $dbStorage->load();

            // Объединяем: cookie-товары добавляем только те, которых нет в БД
            $merged = $dbItems;
            foreach ($cookieItems as $cookieItem) {
                $exists = false;
                foreach ($dbItems as $dbItem) {
                    if ($dbItem->getId() === $cookieItem->getId()) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $merged[] = $cookieItem;
                }
            }

            $dbStorage->save($merged);
            $cookieStorage->save([]);
        }

        $this->storage = $dbStorage;
        return $this->storage;
    }
}
