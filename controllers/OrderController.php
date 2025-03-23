<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Order;
use app\models\OrderItem;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['create' => ['GET', 'POST']]],
        ];
    }

    public function actionCreate()
    {
        $cart = Yii::$app->cart;

        if (empty($cart->getItems())) {
            return $this->redirect(['cart/index']);
        }

        $model = new Order();
        $model->total = $cart->getTotalSum();

        if (!Yii::$app->user->isGuest) {
            $model->user_id = Yii::$app->user->id;
            $model->name = Yii::$app->user->identity->username;
            $model->email = Yii::$app->user->identity->email;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

            $cart->clear();
            Yii::$app->session->setFlash('success', 'Заказ успешно оформлен!');

            return $this->redirect(['cart/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatus($id, $status)
    {
        $model = Order::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Заказ не найден');
        }

        $model->status = $status;
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Статус заказа обновлён');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionComment($id)
    {
        $model = Order::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->session->setFlash('success', 'Комментарий сохранён');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }


}
