<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

return [

    // Заказы
    [
        'label' => 'Заказы',
        'iconClass' => 'bi bi-bag-check me-1',
        'url' => ['/Shop/backend/order/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/order');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Товары
    [
        'label' => 'Товары',
        'iconClass' => 'bi bi-box-seam me-1',
        'url' => ['/Shop/backend/product/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/product');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Бренды
    [
        'label' => 'Бренды',
        'iconClass' => 'bi bi-bookmark-star me-1',
        'url' => ['/Shop/backend/brand/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/brand');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Категории
    [
        'label' => 'Категории',
        'iconClass' => 'bi bi-list-ol me-1',
        'url' => ['/Shop/backend/category/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/category');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Характеристики
    [
        'label' => 'Характеристики',
        'iconClass' => 'bi bi-sliders me-1',
        'url' => ['/Shop/backend/characteristic/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/characteristic');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Доставка
    [
        'label' => 'Доставка',
        'iconClass' => 'bi bi-truck me-1',
        'url' => ['/Shop/backend/delivery/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/delivery');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Скидки
    [
        'label' => 'Скидки',
        'iconClass' => 'bi bi-percent me-1',
        'url' => ['/Shop/backend/discount/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/discount');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

    // Теги
    [
        'label' => 'Теги',
        'iconClass' => 'bi bi-tags me-1',
        'url' => ['/Shop/backend/tag/index'],
        'active' => static function () {
            return str_contains(\Yii::$app->request->url, 'Shop/backend/tag');
        },
        '_meta' => [
            'placements' => [
                [
                    'location' => 'left-sidebar',
                    'group' => 'Shop',
                    'groupIcon' => 'bi bi-shop',
                    'priority' => 100,
                    'groupPriority' => 100,
                ],
            ],
        ],
    ],

];
