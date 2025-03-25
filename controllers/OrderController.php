<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Order;
use app\models\OrderItem;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['GET', 'POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['my-orders', 'view-order'],
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
     * Форма создания заказа
     */
    public function actionCreate()
    {
        $cart = Yii::$app->cart;

        // Проверяем, не пуста ли корзина
        if (empty($cart->getItems())) {
            Yii::$app->session->setFlash('error', 'Ваша корзина пуста. Добавьте товары перед оформлением заказа.');
            return $this->redirect(['cart/index']);
        }

        // Создаем модель заказа
        $model = new Order();
        $model->status = Order::STATUS_PENDING;
        $model->total = $cart->getTotalSum();
        
        // Заполняем данные пользователя, если он авторизован
        $model->fillUserData();

        // Обработка формы заказа
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Сохраняем заказ
            if ($model->save()) {
                // Сохраняем элементы заказа
                foreach ($cart->getProductData() as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $model->id;
                    $orderItem->product_id = $item['product']->id;
                    $orderItem->name = $item['product']->name;
                    $orderItem->price = $item['product']->price;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->sum = $item['sum'];
                    $orderItem->save();
                }

                // Очищаем корзину
                $cart->clear();

                // Отправляем уведомление на email администратору
                $this->sendOrderNotification($model);

                // Показываем страницу с подтверждением заказа
                Yii::$app->session->setFlash('success', 'Ваш заказ успешно оформлен! Наш менеджер свяжется с вами в ближайшее время.');
                return $this->redirect(['success', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка при оформлении заказа. Пожалуйста, попробуйте снова.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Страница успешного оформления заказа
     */
    public function actionSuccess($id)
    {
        $order = Order::findOne($id);
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        return $this->render('success', [
            'order' => $order,
        ]);
    }

    /**
     * Список заказов пользователя
     */
    public function actionMyOrders()
    {
        $orders = Order::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('my-orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Просмотр заказа пользователем
     */
    public function actionViewOrder($id)
    {
        $order = Order::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id
        ]);

        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден или у вас нет доступа к нему');
        }

        return $this->render('view-order', [
            'order' => $order,
        ]);
    }

    /**
     * Отправка уведомления о новом заказе администратору
     */
    protected function sendOrderNotification($order)
    {
        try {
            Yii::$app->mailer->compose('order-notification', ['order' => $order])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Новый заказ #' . $order->id)
                ->send();
        } catch (\Exception $e) {
            Yii::error('Ошибка отправки уведомления о заказе: ' . $e->getMessage());
        }
    }
}