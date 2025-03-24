<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use app\models\Order;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Отображает главную страницу административной панели
     * 
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * Возвращает суммарную статистику для использования в API
     * 
     * @return \yii\web\Response
     */
    public function actionGetStatistics()
    {
        // Этот метод может быть использован как AJAX-эндпоинт для получения обновленной статистики
        // без перезагрузки страницы
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Статистика по товарам и категориям
        $totalProducts = Product::find()->count();
        $activeProducts = Product::find()->where(['status' => 1])->count();
        
        // Статистика по заказам
        $totalOrders = Order::find()->count();
        $pendingOrders = Order::find()->where(['status' => 'pending'])->count();
        $completedOrders = Order::find()->where(['status' => 'done'])->count();
        $cancelledOrders = Order::find()->where(['status' => 'cancelled'])->count();
        
        // Сумма продаж
        $totalSales = 0;
        try {
            // Пытаемся получить сумму разными способами
            $tableSchema = Yii::$app->db->schema->getTableSchema('order');
            if ($tableSchema && $tableSchema->getColumn('total')) {
                $totalSales = Order::find()->where(['status' => 'done'])->sum('total') ?: 0;
            } else {
                // Если столбца 'total' нет, пробуем получить сумму из связанных элементов заказа
                $completedOrders = Order::find()->where(['status' => 'done'])->all();
                foreach ($completedOrders as $order) {
                    foreach ($order->items as $item) {
                        $totalSales += ($item->price * $item->quantity);
                    }
                }
            }
        } catch (\Exception $e) {
            Yii::warning('Ошибка при расчете суммы продаж: ' . $e->getMessage());
        }
        
        // Статистика по пользователям
        $totalUsers = User::find()->count();
        $activeUsers = User::find()->where(['status' => User::STATUS_ACTIVE])->count();
        
        return [
            'products' => [
                'total' => $totalProducts,
                'active' => $activeProducts,
                'inactive' => $totalProducts - $activeProducts,
            ],
            'orders' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'completed' => $completedOrders,
                'cancelled' => $cancelledOrders,
            ],
            'sales' => [
                'total' => $totalSales,
                'formatted' => number_format($totalSales, 0, '.', ' ') . ' ₽',
            ],
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
            ],
        ];
    }
}