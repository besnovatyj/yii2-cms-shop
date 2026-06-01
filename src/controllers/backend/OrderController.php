<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\backend;

use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\forms\backend\order\OrderEditForm;
use Besnovatyj\Shop\forms\backend\search\OrderSearch;
use Besnovatyj\Shop\services\manage\OrderManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер управления заказами.
 */
class OrderController extends Controller
{
    private OrderManageService $service;

    public function __construct($id, $module, OrderManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel  = new OrderSearch();
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
        return $this->render('view', ['order' => $this->findModel($id)]);
    }

    /**
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $order = $this->findModel($id);
        $form  = new OrderEditForm($order);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($order->id, $form);
                return $this->redirect(['view', 'id' => $order->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('update', ['model' => $form, 'order' => $order]);
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
            Yii::$app->errorHandler->logException($e);
            $this->setErrorFlash($e);
        }
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Order
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Order
    {
        if (($model = Order::findOne($id)) !== null) {
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
