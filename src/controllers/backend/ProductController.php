<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\backend;

use Besnovatyj\Images\helpers\ImageActionsMap;
use Besnovatyj\Shop\entities\product\Characteristic;
use Besnovatyj\Shop\entities\product\Photo;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\forms\backend\product\ModificationForm;
use Besnovatyj\Shop\forms\backend\product\PriceForm;
use Besnovatyj\Shop\forms\backend\product\ProductCreateForm;
use Besnovatyj\Shop\forms\backend\product\ProductEditForm;
use Besnovatyj\Shop\forms\backend\product\QuantityForm;
use Besnovatyj\Shop\forms\backend\product\ReviewEditForm;
use Besnovatyj\Shop\forms\backend\search\ProductSearch;
use Besnovatyj\Shop\image\ProductImageOwner;
use Besnovatyj\Shop\repositories\ProductRepository;
use Besnovatyj\Shop\services\manage\ModificationManageService;
use Besnovatyj\Shop\services\manage\ProductManageService;
use Besnovatyj\Shop\services\manage\ReviewManageService;
use DomainException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер управления товарами.
 *
 * Фото товара загружаются через HTMX-friendly standalone actions (ImageActionsMap).
 * Модификации и отзывы управляются через отдельные actions.
 */
class ProductController extends Controller
{
    private ProductManageService      $service;
    private ModificationManageService $modService;
    private ReviewManageService       $reviewService;
    private ProductRepository         $productRepo;

    public function __construct(
        $id,
        $module,
        ProductManageService      $service,
        ModificationManageService $modService,
        ReviewManageService       $reviewService,
        ProductRepository         $productRepo,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
        $this->service       = $service;
        $this->modService    = $modService;
        $this->reviewService = $reviewService;
        $this->productRepo   = $productRepo;
    }

    /**
     * Регистрирует standalone image-actions через ImageActionsMap.
     *
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return ImageActionsMap::get(
            Photo::class,
            fn(int $id) => new ProductImageOwner($this->productRepo->get($id), $this->productRepo),
        );
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
                    'activate'       => ['POST'],
                    'draft'          => ['POST'],
                    'delete'         => ['POST'],
                    'add-image'      => ['POST'],
                    'delete-image'   => ['POST'],
                    'set-main-image' => ['POST'],
                    'get-images'     => ['POST'],
                    'set-new-sort'   => ['POST'],
                    // Модификации (add/update рендерят форму на GET, поэтому без ограничения)
                    'delete-modification' => ['POST'],
                    // Отзывы
                    'activate-review' => ['POST'],
                    'draft-review'    => ['POST'],
                    'delete-review'   => ['POST'],
                ],
            ],
        ];
    }

    // -----------------------------------------------------------------------
    // CRUD товара
    // -----------------------------------------------------------------------

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel  = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $product = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
            'query'      => $product->getModifications()->orderBy('name'),
            'pagination' => false,
        ]);

        $reviewsProvider = new ActiveDataProvider([
            'query'      => $product->getReviews()->orderBy(['id' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('view', [
            'product'               => $product,
            'modificationsProvider' => $modificationsProvider,
            'reviewsProvider'       => $reviewsProvider,
        ]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $form = new ProductCreateForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $product = $this->service->create($form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('create', [
            'model'           => $form,
            'characteristics' => $this->loadCharacteristics(),
        ]);
    }

    /**
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $product = $this->findModel($id);
        $form    = new ProductEditForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('update', [
            'model'           => $form,
            'product'         => $product,
            'characteristics' => $this->loadCharacteristics(),
        ]);
    }

    /**
     * Загружает все характеристики, отсортированные для рендера в форме товара.
     *
     * @return Characteristic[]
     */
    private function loadCharacteristics(): array
    {
        return Characteristic::find()->orderBy(['sort' => SORT_ASC, 'name' => SORT_ASC])->all();
    }

    /**
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionPrice(int $id): Response|string
    {
        $product = $this->findModel($id);
        $form    = new PriceForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changePrice($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('price', ['model' => $form, 'product' => $product]);
    }

    /**
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionQuantity(int $id): Response|string
    {
        $product = $this->findModel($id);
        $form    = new QuantityForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changeQuantity($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('quantity', ['model' => $form, 'product' => $product]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionActivate(int $id): Response
    {
        try {
            $this->service->activate($id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDraft(int $id): Response
    {
        try {
            $this->service->draft($id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->service->remove($id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['index']);
    }

    // -----------------------------------------------------------------------
    // Модификации
    // -----------------------------------------------------------------------

    /**
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionAddModification(int $id): Response|string
    {
        $product = $this->findModel($id);
        $form    = new ModificationForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->modService->add($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('_modification_form', ['model' => $form, 'product' => $product]);
    }

    /**
     * @param int $id
     * @param int $modification_id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdateModification(int $id, int $modification_id): Response|string
    {
        $product      = $this->findModel($id);
        $modification = \Besnovatyj\Shop\entities\product\Modification::find()
            ->andWhere(['id' => $modification_id, 'product_id' => $product->id])
            ->one();
        if (!$modification) {
            throw new \yii\web\NotFoundHttpException('Модификация не найдена.');
        }
        $form = new ModificationForm($modification);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->modService->edit($product->id, $modification_id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('_modification_form', ['model' => $form, 'product' => $product]);
    }

    /**
     * @param int $id
     * @param int $modification_id
     * @return Response
     */
    public function actionDeleteModification(int $id, int $modification_id): Response
    {
        try {
            $this->modService->remove($id, $modification_id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    // -----------------------------------------------------------------------
    // Отзывы
    // -----------------------------------------------------------------------

    /**
     * @param int $id
     * @param int $review_id
     * @return Response
     */
    public function actionActivateReview(int $id, int $review_id): Response
    {
        try {
            $this->reviewService->activate($id, $review_id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'reviews']);
    }

    /**
     * @param int $id
     * @param int $review_id
     * @return Response
     */
    public function actionDraftReview(int $id, int $review_id): Response
    {
        try {
            $this->reviewService->draft($id, $review_id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'reviews']);
    }

    /**
     * @param int $id
     * @param int $review_id
     * @return Response
     */
    public function actionDeleteReview(int $id, int $review_id): Response
    {
        try {
            $this->reviewService->remove($id, $review_id);
        } catch (DomainException $e) {
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'reviews']);
    }

    // -----------------------------------------------------------------------
    // Вспомогательные методы
    // -----------------------------------------------------------------------

    /**
     * @param int $id
     * @return Product
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Product
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запись не найдена.');
    }

    /**
     * Устанавливает flash-сообщение об ошибке.
     */
    private function setErrorFlash(DomainException $e): void
    {
        $message = YII_DEBUG ? VarDumper::dumpAsString($e->getMessage()) : 'Ошибка';
        Yii::$app->session->setFlash('error', $message);
    }
}
