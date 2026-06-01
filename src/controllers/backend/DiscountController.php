<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\backend;

use Besnovatyj\Shop\entities\Discount;
use Besnovatyj\Shop\forms\backend\DiscountForm;
use Besnovatyj\Shop\services\manage\DiscountManageService;
use DomainException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер управления скидками.
 */
class DiscountController extends Controller
{
    private DiscountManageService $service;

    public function __construct($id, $module, DiscountManageService $service, $config = [])
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
        $dataProvider = new ActiveDataProvider([
            'query' => Discount::find()->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', ['discount' => $this->findModel($id)]);
    }

    /**
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $form = new DiscountForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $discount = $this->service->create($form);
                return $this->redirect(['view', 'id' => $discount->id]);
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
        $discount = $this->findModel($id);
        $form     = new DiscountForm($discount);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($discount->id, $form);
                return $this->redirect(['view', 'id' => $discount->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $this->setErrorFlash($e);
            }
        }
        return $this->render('update', ['model' => $form, 'discount' => $discount]);
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
     * @return Discount
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Discount
    {
        if (($model = Discount::findOne($id)) !== null) {
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
