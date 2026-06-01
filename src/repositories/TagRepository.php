<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\repositories;

use Besnovatyj\Shop\entities\Tag;
use RuntimeException;
use Throwable;

/**
 * Репозиторий тегов.
 */
class TagRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(int $id): Tag
    {
        if (!$tag = Tag::findOne($id)) {
            throw new NotFoundException('Тег не найден.');
        }
        return $tag;
    }

    /**
     * Ищет тег по slug.
     */
    public function findBySlug(string $slug): ?Tag
    {
        return Tag::findOne(['slug' => $slug]);
    }

    /**
     * Ищет тег по имени.
     */
    public function findByName(string $name): ?Tag
    {
        return Tag::findOne(['name' => $name]);
    }

    /**
     * @throws RuntimeException
     */
    public function save(Tag $tag): void
    {
        if (!$tag->save()) {
            throw new RuntimeException('Ошибка сохранения тега.');
        }
    }

    /**
     * @throws Throwable
     */
    public function remove(Tag $tag): void
    {
        if (!$tag->delete()) {
            throw new RuntimeException('Ошибка удаления тега.');
        }
    }

    /**
     * Удаляет теги, не привязанные ни к одному товару.
     */
    public function deleteOrphanTags(): void
    {
        $usedIds = (new \yii\db\Query())
            ->select('DISTINCT tag_id')
            ->from('{{%shop_tag_assignments}}')
            ->column();

        Tag::deleteAll(['not in', 'id', $usedIds ?: [0]]);
    }
}
