<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\frontend;

use Besnovatyj\Shop\forms\frontend\AddToCartForm;
use Besnovatyj\Shop\forms\frontend\ReviewForm;
use Besnovatyj\Shop\forms\frontend\search\SearchForm;
use Besnovatyj\Shop\readModels\BrandReadRepository;
use Besnovatyj\Shop\readModels\CategoryReadRepository;
use Besnovatyj\Shop\readModels\ProductReadRepository;
use Besnovatyj\Shop\services\manage\ReviewManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер каталога товаров (фронтенд).
 */
class CatalogController extends Controller
{
    private ProductReadRepository  $products;
    private CategoryReadRepository $categories;
    private BrandReadRepository    $brands;
    private ReviewManageService    $reviewService;

    public function __construct(
        $id,
        $module,
        ProductReadRepository  $products,
        CategoryReadRepository $categories,
        BrandReadRepository    $brands,
        ReviewManageService    $reviewService,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
        $this->products       = $products;
        $this->categories     = $categories;
        $this->brands         = $brands;
        $this->reviewService  = $reviewService;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'add-review' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Главная страница каталога.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = $this->products->getAll();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Страница категории.
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCategory(int $id): string
    {
        $category = $this->categories->find($id);
        if (!$category) {
            throw new NotFoundHttpException('Категория не найдена.');
        }

        $dataProvider = $this->products->getAllByCategory($category);

        return $this->render('category', [
            'category'     => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Страница бренда.
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBrand(int $id): string
    {
        $brand = $this->brands->find($id);
        if (!$brand) {
            throw new NotFoundHttpException('Бренд не найден.');
        }

        $dataProvider = $this->products->getAllByBrand($brand);

        return $this->render('brand', [
            'brand'        => $brand,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Поиск товаров.
     *
     * @return string
     */
    public function actionSearch(): string
    {
        $form = new SearchForm();
        $form->load(Yii::$app->request->queryParams);
        $form->validate();

        $dataProvider = $this->products->search($form);

        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'searchForm'   => $form,
        ]);
    }

    /**
     * Страница товара.
     *
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionProduct(int $id): Response|string
    {
        $product = $this->products->find($id);
        if (!$product) {
            throw new NotFoundHttpException('Товар не найден.');
        }

        $cartForm   = new AddToCartForm($product);
        $reviewForm = new ReviewForm();

        if ($reviewForm->load(Yii::$app->request->post()) && $reviewForm->validate()) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Для добавления отзыва необходимо войти.');
                return $this->goReferer();
            }
            try {
                $this->reviewService->create($id, Yii::$app->user->id, (int) $reviewForm->vote, (string) $reviewForm->text);
                Yii::$app->session->setFlash('success', 'Отзыв отправлен на модерацию.');
                return $this->redirect(['product', 'id' => $id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $msg = YII_DEBUG ? VarDumper::dumpAsString($e->getMessage()) : 'Ошибка';
                Yii::$app->session->setFlash('error', $msg);
            }
        }

        return $this->render('product', [
            'product'    => $product,
            'cartForm'   => $cartForm,
            'reviewForm' => $reviewForm,
        ]);
    }
}
