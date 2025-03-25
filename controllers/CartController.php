<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Product;

class CartController extends Controller
{
    public function actionIndex()
    {
        $items = Yii::$app->cart->getProductData();
        $total = Yii::$app->cart->getTotalSum();

        return $this->render('index', compact('items', 'total'));
    }

    public function actionAdd($id, $quantity = 1)
    {
        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException('Товар не найден');
        }

        $quantity = max(1, (int)$quantity);
        Yii::$app->cart->add($id, $quantity);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $totalSum = Yii::$app->cart->getTotalSum();

            return [
                'success' => true,
                'count' => count(Yii::$app->cart->getItems()),
                'totalCount' => Yii::$app->cart->getTotalCount(),
                'total' => Yii::$app->formatter->asCurrency($totalSum, 'RUB'),
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => Yii::$app->formatter->asCurrency($product->price, 'RUB'),
                    'image' => $product->image,
                ],
                'message' => 'Товар успешно добавлен в корзину',
            ];
        }

        return $this->redirect(['cart/index']);
    }

    public function actionRemove($id)
    {
        Yii::$app->cart->remove($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $totalSum = Yii::$app->cart->getTotalSum();

            return [
                'success' => true,
                'count' => count(Yii::$app->cart->getItems()),
                'totalCount' => Yii::$app->cart->getTotalCount(),
                'subtotal' => Yii::$app->formatter->asCurrency($totalSum, 'RUB'),
                'total' => Yii::$app->formatter->asCurrency($totalSum, 'RUB'),
                'message' => 'Товар удален из корзины',
            ];
        }

        return $this->redirect(['cart/index']);
    }

    public function actionClear()
    {
        Yii::$app->cart->clear();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true, 'message' => 'Корзина очищена'];
        }

        return $this->redirect(['cart/index']);
    }

    public function actionUpdateQuantity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $quantity = max(1, (int)Yii::$app->request->post('quantity'));

        if (!$id || $quantity < 1) {
            return ['success' => false, 'message' => 'Некорректные данные'];
        }

        $product = Product::findOne($id);
        if (!$product) {
            return ['success' => false, 'message' => 'Товар не найден'];
        }

        Yii::$app->cart->update($id, $quantity);

        $itemSum = $product->price * $quantity;
        $subtotal = Yii::$app->cart->getTotalSum();
        $delivery = $subtotal < 3000 ? 300 : 0;
        $total = $subtotal + $delivery;

        return [
            'success' => true,
            'item_sum' => Yii::$app->formatter->asCurrency($itemSum, 'RUB'),
            'subtotal' => Yii::$app->formatter->asCurrency($subtotal, 'RUB'),
            'delivery' => $delivery === 0 ? 'Бесплатно' : Yii::$app->formatter->asCurrency($delivery, 'RUB'),
            'total' => Yii::$app->formatter->asCurrency($total, 'RUB'),
            'count' => count(Yii::$app->cart->getItems()),
            'totalCount' => Yii::$app->cart->getTotalCount(),
            'message' => 'Количество товара обновлено',
        ];
    }
}
