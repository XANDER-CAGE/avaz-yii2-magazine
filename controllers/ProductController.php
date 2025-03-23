<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;

class ProductController extends Controller
{
    public function actionIndex($category_id = null)
    {
        $query = Product::find()->with('category');

        if ($category_id) {
            $query->andWhere(['category_id' => $category_id]);
        }

        $products = $query->all();
        $categories = Category::find()->all();

        return $this->render('index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $category_id,
        ]);
    }

    public function actionView($id)
    {
        $product = Product::findOne($id);
        if (!$product) {
            throw new \yii\web\NotFoundHttpException("Product not found");
        }

        return $this->render('view', [
            'product' => $product,
        ]);
    }
}
