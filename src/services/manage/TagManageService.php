<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\services\manage;

use Besnovatyj\Shop\entities\Tag;
use Besnovatyj\Shop\forms\backend\TagForm;
use Besnovatyj\Shop\repositories\TagRepository;
use Throwable;

/**
 * Сервис управления тегами товаров.
 */
class TagManageService
{
    public function __construct(
        private readonly TagRepository $tags,
    ) {}

    /**
     * Создаёт тег.
     */
    public function create(TagForm $form): Tag
    {
        $tag = Tag::create($form->name, $form->slug);
        $this->tags->save($tag);
        return $tag;
    }

    /**
     * Редактирует тег.
     */
    public function edit(int $id, TagForm $form): void
    {
        $tag = $this->tags->get($id);
        $tag->edit($form->name, $form->slug);
        $this->tags->save($tag);
    }

    /**
     * Удаляет тег.
     *
     * @throws Throwable
     */
    public function remove(int $id): void
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }
}
