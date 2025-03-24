<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use yii\data\Pagination;


class ProductController extends Controller
{
    public function actionIndex($category_id = null, $sort = null)
{
    $query = Product::find()->with('category');

    if ($category_id) {
        $query->andWhere(['category_id' => $category_id]);
    }

    // Примитивная сортировка
    switch ($sort) {
        case 'name':
            $query->orderBy(['name' => SORT_ASC]);
            break;
        case 'price':
            $query->orderBy(['price' => SORT_ASC]);
            break;
        case '-price':
            $query->orderBy(['price' => SORT_DESC]);
            break;
        case '-created_at':
            $query->orderBy(['created_at' => SORT_DESC]);
            break;
        default:
            $query->orderBy(['id' => SORT_DESC]);
    }

    $pages = new Pagination([
        'totalCount' => $query->count(),
        'pageSize' => 9,
        'defaultPageSize' => 9,
    ]);

    $products = $query->offset($pages->offset)->limit($pages->limit)->all();

    $categories = Category::find()->all();

    return $this->render('index', [
        'products' => $products,
        'categories' => $categories,
        'selectedCategory' => $category_id,
        'pages' => $pages,
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
