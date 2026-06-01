<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\frontend;

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\forms\frontend\AddToCartForm;
use Besnovatyj\Shop\readModels\ProductReadRepository;
use Besnovatyj\Shop\services\CartService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер корзины (фронтенд).
 *
 * Операции изменения количества и удаления возвращают HTML-фрагмент корзины
 * через HTMX (hx-swap), чтобы не требовать полной перезагрузки страницы.
 */
class CartController extends Controller
{
    private CartService           $service;
    private ProductReadRepository $products;

    public function __construct(
        $id,
        $module,
        CartService           $service,
        ProductReadRepository $products,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
        $this->service  = $service;
        $this->products = $products;
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
                    // 'add' допускает GET (показ формы выбора модификации) и POST (отправка)
                    'quantity' => ['POST'],
                    'remove'   => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Страница корзины.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'cart' => $this->service->getCart(),
        ]);
    }

    /**
     * Добавление товара в корзину.
     *
     * При наличии модификаций отображает форму выбора модификации.
     * Иначе — добавляет сразу.
     *
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionAdd(int $id): Response|string
    {
        $product = $this->products->find($id);
        if (!$product) {
            throw new NotFoundHttpException('Товар не найден.');
        }

        $form = new AddToCartForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->add($product->id, $form->modification, $form->quantity);
                Yii::$app->session->setFlash('success', 'Товар добавлен в корзину.');
                return $this->redirect(['index']);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }

        return $this->render('add', [
            'product' => $product,
            'model'   => $form,
        ]);
    }

    /**
     * Обновление количества товаров в корзине.
     *
     * Принимает массив quantity_data вида [{id, modId, quantity}].
     * Если запрос сделан через HTMX, возвращает HTML-фрагмент корзины.
     *
     * @return Response|string
     */
    public function actionQuantity(): Response|string
    {
        $quantityData = Yii::$app->request->post('quantity_data', []);
        try {
            $this->service->changeQuantity($quantityData);
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            $this->setErrorFlash($e);
        }

        // HTMX partial update
        if (Yii::$app->request->headers->get('HX-Request')) {
            return $this->renderPartial('_items', [
                'cart' => $this->service->getCart(),
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Удаление позиции из корзины.
     *
     * @param int $id         ID товара
     * @param int|null $modId ID модификации (если есть)
     * @return Response|string
     */
    public function actionRemove(int $id, ?int $modId = null): Response|string
    {
        try {
            $this->service->removeByProduct($id, $modId);
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            $this->setErrorFlash($e);
        }

        // HTMX partial update
        if (Yii::$app->request->headers->get('HX-Request')) {
            return $this->renderPartial('_items', [
                'cart' => $this->service->getCart(),
            ]);
        }

        return $this->redirect(['index']);
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
