<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\backend;

use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\forms\backend\BrandForm;
use Besnovatyj\Shop\forms\backend\search\BrandSearch;
use Besnovatyj\Shop\services\manage\BrandManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер управления брендами.
 */
class BrandController extends Controller
{
    private BrandManageService $service;

    public function __construct($id, $module, BrandManageService $service, $config = [])
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
        $searchModel  = new BrandSearch();
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
        return $this->render('view', [
            'brand' => $this->findModel($id),
        ]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $form = new BrandForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $brand = $this->service->create($form);
                return $this->redirect(['view', 'id' => $brand->id]);
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
        $brand = $this->findModel($id);
        $form  = new BrandForm($brand);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($brand->id, $form);
                return $this->redirect(['view', 'id' => $brand->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('update', ['model' => $form, 'brand' => $brand]);
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
     * @return Brand
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Brand
    {
        if (($model = Brand::findOne($id)) !== null) {
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
