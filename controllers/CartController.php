<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Product;

class CartController extends Controller
{
    public function actionIndex()
    {
        $items = Yii::$app->cart->getProductData();
        $total = Yii::$app->cart->getTotalSum();

        return $this->render('index', compact('items', 'total'));
    }

    public function actionAdd($id)
    {
        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException('Товар не найден');
        }

        Yii::$app->cart->add($id, 1);
        return $this->redirect(['cart/index']);
    }

    public function actionRemove($id)
    {
        Yii::$app->cart->remove($id);
        return $this->redirect(['cart/index']);
    }

    public function actionClear()
    {
        Yii::$app->cart->clear();
        return $this->redirect(['cart/index']);
    }
}
