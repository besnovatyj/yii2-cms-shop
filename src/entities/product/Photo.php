<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use Besnovatyj\Images\base\BaseImage;

/**
 * Фотография товара.
 *
 * @property int    $id
 * @property int    $product_id
 * @property string $file
 * @property int    $sort
 */
class Photo extends BaseImage
{
    /**
     * {@inheritdoc}
     */
    protected static function getParentAttribute(): string
    {
        return 'product_id';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getStorageName(): string
    {
        return 'Shop';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getThumbProfiles(): array
    {
        return [
            'admin'                             => ['width' => 100, 'height' => 70],   // /backend/product/index
            'thumb'                             => ['width' => 640, 'height' => 480],  // /backend/product/view
            'cart_list'                         => ['width' => 150, 'height' => 150],
            'cart_widget_list'                  => ['width' => 57,  'height' => 57],
            'catalog_list'                      => ['width' => 228, 'height' => 228],
            'catalog_product_additional'        => ['width' => 66,  'height' => 66],
            'catalog_product_main'              => ['width' => 750, 'height' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_photos}}';
    }
}
