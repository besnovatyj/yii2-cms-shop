<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\entities\product;

use Besnovatyj\Meta\Meta;
use Besnovatyj\Meta\MetaBehavior;
use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\entities\category\Category;
use Besnovatyj\Shop\entities\product\events\ProductAppearedInStock;
use Besnovatyj\Shop\entities\product\queries\ProductQuery;
use Besnovatyj\Shop\entities\Tag;
use Besnovatyj\PessimisticLock\PessimisticLockBehavior;
use Besnovatyj\DomainEvents\AggregateRoot;
use Besnovatyj\DomainEvents\EventTrait;
use DateTimeImmutable;
use DomainException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Товар (Aggregate Root).
 *
 * Бизнес-логика товара (статусы, цена, остаток, checkout).
 * Управление связями (категории, теги, фото, модификации, характеристики, отзывы)
 * вынесено в соответствующие сервисы и репозитории.
 *
 * @property int         $id
 * @property int         $created_at
 * @property string      $code           Артикул
 * @property string      $name           Название
 * @property string|null $description    Описание
 * @property int|null    $category_id    ID основной категории
 * @property int|null    $brand_id       ID бренда
 * @property int         $price_old      Старая цена (в копейках)
 * @property int         $price_new      Текущая цена (в копейках)
 * @property float|null  $rating         Средний рейтинг
 * @property int|null    $main_photo_id  ID главной фотографии
 * @property int         $status         Статус (черновик / активен)
 * @property int         $weight         Масса (граммы)
 * @property int         $quantity       Суммарный остаток
 *
 * @property Meta        $meta
 * @property Brand|null  $brand
 * @property Category|null $category
 * @property CategoryAssignment[] $categoryAssignments
 * @property Category[]  $categories
 * @property TagAssignment[] $tagAssignments
 * @property Tag[]       $tags
 * @property Modification[] $modifications
 * @property Value[]     $values
 * @property Photo[]     $photos
 * @property Photo|null  $mainPhoto
 * @property Review[]    $reviews
 * @property RelatedAssignment[] $relatedAssignments
 * @property Product[]   $relateds
 *
 * @mixin PessimisticLockBehavior
 */
class Product extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    public const int STATUS_DRAFT  = 0;
    public const int STATUS_ACTIVE = 1;

    public Meta $meta;

    /**
     * Создаёт новый товар.
     */
    public static function create(
        ?int   $brandId,
        int    $categoryId,
        string $code,
        string $name,
        ?string $description,
        int    $weight,
        int    $quantity,
        Meta   $meta,
    ): self {
        $product              = new static();
        $product->brand_id    = $brandId;
        $product->category_id = $categoryId;
        $product->code        = $code;
        $product->name        = $name;
        $product->description = $description;
        $product->weight      = $weight;
        $product->quantity    = $quantity;
        $product->meta        = $meta;
        $product->status      = self::STATUS_DRAFT;
        $product->created_at  = (new DateTimeImmutable())->getTimestamp();
        return $product;
    }

    /**
     * Редактирует базовые поля товара.
     */
    public function edit(
        ?int   $brandId,
        string $code,
        string $name,
        ?string $description,
        int    $weight,
        Meta   $meta,
    ): void {
        $this->brand_id    = $brandId;
        $this->code        = $code;
        $this->name        = $name;
        $this->description = $description;
        $this->weight      = $weight;
        $this->meta        = $meta;
    }

    /**
     * Устанавливает основную категорию товара.
     */
    public function changeMainCategory(int $categoryId): void
    {
        $this->category_id = $categoryId;
    }

    /**
     * Устанавливает цены товара.
     */
    public function setPrice(int $priceNew, int $priceOld): void
    {
        $this->price_new = $priceNew;
        $this->price_old = $priceOld;
    }

    /**
     * Изменяет остаток товара без модификаций.
     *
     * @throws DomainException Если у товара есть модификации — изменение нужно через них.
     */
    public function changeQuantity(int $quantity): void
    {
        if ($this->modifications) {
            throw new DomainException('У товара есть модификации — изменяйте количество через них.');
        }
        $this->setQuantity($quantity);
    }

    /**
     * Устанавливает id главной фотографии.
     */
    public function setMainPhoto(?int $photoId): void
    {
        $this->main_photo_id = $photoId;
    }

    /**
     * Обновляет средний рейтинг товара на основе переданных активных отзывов.
     *
     * @param Review[] $activeReviews
     */
    public function recalculateRating(array $activeReviews): void
    {
        $count = count($activeReviews);
        if ($count === 0) {
            $this->rating = null;
            return;
        }
        $total = array_sum(array_map(fn(Review $r) => $r->getRating(), $activeReviews));
        $this->rating = $total / $count;
    }

    /**
     * Публикует товар.
     *
     * @throws DomainException
     */
    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Товар уже опубликован.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * Переводит товар в черновик.
     *
     * @throws DomainException
     */
    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('Товар уже является черновиком.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    /**
     * Проверяет, опубликован ли товар.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Проверяет, является ли товар черновиком.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Проверяет, есть ли товар в наличии.
     */
    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Проверяет, можно ли изменить количество товара напрямую.
     */
    public function canChangeQuantity(): bool
    {
        return !$this->modifications;
    }

    /**
     * Проверяет возможность оформления заказа.
     */
    public function canBeCheckout(?int $modificationId, int $quantity): bool
    {
        if ($modificationId) {
            foreach ($this->modifications as $modification) {
                if ($modification->id === $modificationId) {
                    return $quantity <= $modification->quantity;
                }
            }
            return false;
        }
        return $quantity <= $this->quantity;
    }

    /**
     * Оформляет покупку: уменьшает остаток.
     *
     * @throws DomainException
     */
    public function checkout(?int $modificationId, int $quantity): void
    {
        if ($modificationId) {
            foreach ($this->modifications as $modification) {
                if ($modification->id === $modificationId) {
                    $modification->checkout($quantity);
                    // Обновляем суммарный остаток
                    $this->setQuantity(
                        array_sum(array_map(fn(Modification $m) => $m->quantity, $this->modifications))
                    );
                    return;
                }
            }
            throw new DomainException('Модификация не найдена.');
        }

        if ($quantity > $this->quantity) {
            throw new DomainException("Доступно только {$this->quantity} единиц.");
        }
        $this->setQuantity($this->quantity - $quantity);
    }

    /**
     * Возвращает SEO-заголовок.
     */
    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->name;
    }

    // ── Relations ─────────────────────────────────────────────────────────

    /** @return ActiveQuery */
    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    /** @return ActiveQuery */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /** @return ActiveQuery */
    public function getCategoryAssignments(): ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    /** @return ActiveQuery */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');
    }

    /** @return ActiveQuery */
    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    /** @return ActiveQuery */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    /** @return ActiveQuery */
    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
    }

    /** @return ActiveQuery */
    public function getValues(): ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    /** @return ActiveQuery */
    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    /** @return ActiveQuery */
    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);
    }

    /** @return ActiveQuery */
    public function getRelatedAssignments(): ActiveQuery
    {
        return $this->hasMany(RelatedAssignment::class, ['product_id' => 'id']);
    }

    /** @return ActiveQuery */
    public function getRelateds(): ActiveQuery
    {
        return $this->hasMany(self::class, ['id' => 'related_id'])->via('relatedAssignments');
    }

    /** @return ActiveQuery */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['product_id' => 'id']);
    }

    // ── Private ───────────────────────────────────────────────────────────

    /**
     * Устанавливает количество и при необходимости генерирует событие.
     */
    private function setQuantity(int $quantity): void
    {
        if ($this->quantity === 0 && $quantity > 0) {
            $this->recordEvent(new ProductAppearedInStock($this)); // TODO: Вынос в сервис `$entity->recordEvent()`. Там после этого `$repo->save();`
        }
        $this->quantity = $quantity;
    }

    // ── ActiveRecord ──────────────────────────────────────────────────────

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shop_products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            PessimisticLockBehavior::class,
            ...parent::behaviors(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function find(): ProductQuery
    {
        return new ProductQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'code'        => 'Артикул',
            'name'        => 'Название',
            'description' => 'Описание',
            'brand_id'    => 'Бренд',
            'category_id' => 'Категория',
            'price_new'   => 'Цена',
            'price_old'   => 'Старая цена',
            'rating'      => 'Рейтинг',
            'status'      => 'Статус',
            'weight'      => 'Масса (г)',
            'quantity'    => 'Количество',
        ];
    }
}
