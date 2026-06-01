<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Shop\controllers\frontend;

use Besnovatyj\Shop\cart\Cart;
use Besnovatyj\Shop\forms\frontend\order\OrderForm;
use Besnovatyj\Shop\services\OrderService;
use DomainException;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Контроллер оформления заказа (фронтенд).
 *
 * Доступен только авторизованным пользователям.
 */
class CheckoutController extends Controller
{
    private OrderService $service;
    private Cart         $cart;

    public function __construct($id, $module, OrderService $service, Cart $cart, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->cart    = $cart;
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
     * Страница оформления заказа.
     *
     * @return Response|string
     */
    public function actionIndex(): Response|string
    {
        $form = new OrderForm($this->cart->getWeight());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $order = $this->service->checkout(Yii::$app->user->id, $form);
                Yii::$app->session->setFlash('success', 'Заказ успешно оформлен!');
                return $this->redirect(['/shop/order/view', 'id' => $order->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $msg = YII_DEBUG ? VarDumper::dumpAsString($e->getMessage()) : 'Ошибка оформления заказа.';
                Yii::$app->session->setFlash('error', $msg);
            }
        }

        return $this->render('index', [
            'cart'  => $this->cart,
            'model' => $form,
        ]);
    }
}
