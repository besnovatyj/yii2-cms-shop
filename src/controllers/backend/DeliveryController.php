<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\backend;

use Besnovatyj\Shop\entities\DeliveryMethod;
use Besnovatyj\Shop\forms\backend\DeliveryMethodForm;
use Besnovatyj\Shop\forms\backend\search\DeliveryMethodSearch;
use Besnovatyj\Shop\services\manage\DeliveryManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер управления методами доставки.
 */
class DeliveryController extends Controller
{
    private DeliveryManageService $service;

    public function __construct($id, $module, DeliveryManageService $service, $config = [])
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
                    'delete'     => ['POST'],
                    'activate'   => ['POST'],
                    'deactivate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel  = new DeliveryMethodSearch();
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
        return $this->render('view', ['method' => $this->findModel($id)]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $form = new DeliveryMethodForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $method = $this->service->create($form);
                return $this->redirect(['view', 'id' => $method->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $method = $this->findModel($id);
        $form   = new DeliveryMethodForm($method);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($method->id, $form);
                return $this->redirect(['view', 'id' => $method->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('update', ['model' => $form, 'method' => $method]);
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
     * @return Response
     */
    public function actionActivate(int $id): Response
    {
        try {
            $this->service->activate($id);
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDeactivate(int $id): Response
    {
        try {
            $this->service->deactivate($id);
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            $this->setErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return DeliveryMethod
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): DeliveryMethod
    {
        if (($model = DeliveryMethod::findOne($id)) !== null) {
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
