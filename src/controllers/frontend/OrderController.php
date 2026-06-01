<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\frontend;

use Besnovatyj\Shop\entities\order\Order;
use Besnovatyj\Shop\readModels\OrderReadRepository;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контроллер заказов пользователя (фронтенд).
 *
 * Доступен только авторизованным пользователям.
 */
class OrderController extends Controller
{
    private OrderReadRepository $orders;

    public function __construct($id, $module, OrderReadRepository $orders, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orders = $orders;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Список заказов пользователя.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = $this->orders->getAllByUser(Yii::$app->user->id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр заказа.
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $order = Order::find()
            ->andWhere(['id' => $id, 'user_id' => Yii::$app->user->id])
            ->one();

        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден.');
        }

        return $this->render('view', ['order' => $order]);
    }
}
