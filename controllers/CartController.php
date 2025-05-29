<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Product;

/**
 * CartController реализует функционал корзины покупок
 */
class CartController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['GET', 'POST'],
                    'remove' => ['GET', 'POST'],
                    'update-quantity' => ['POST'],
                    'clear' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['checkout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Только для авторизованных пользователей
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображение содержимого корзины
     */
    public function actionIndex()
    {
        $cart = Yii::$app->cart;
        $items = $cart->getProductData();
        $subtotal = $cart->getTotalSum();
        
        // Расчет доставки
        $deliveryThreshold = Yii::$app->params['freeDeliveryThreshold'] ?? 3000;
        $deliveryCost = $subtotal >= $deliveryThreshold ? 0 : (Yii::$app->params['deliveryCost'] ?? 300);
        $total = $subtotal + $deliveryCost;

        return $this->render('index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'deliveryCost' => $deliveryCost,
            'deliveryThreshold' => $deliveryThreshold,
            'total' => $total,
        ]);
    }

    /**
     * Добавление товара в корзину
     * 
     * @param int $id ID товара
     * @param int $quantity Количество (по умолчанию 1)
     * @return mixed
     * @throws NotFoundHttpException если товар не найден
     * @throws BadRequestHttpException если некорректные данные
     */
    public function actionAdd($id, $quantity = 1)
    {
        // Валидация входных данных
        if (!is_numeric($id) || $id <= 0) {
            throw new BadRequestHttpException('Некорректный ID товара');
        }

        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException('Товар не найден');
        }

        // Проверка доступности товара
        if (!$product->isAvailable()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => 'Товар недоступен для покупки',
                ];
            }
            
            Yii::$app->session->setFlash('error', 'Товар недоступен для покупки');
            return $this->redirect(['product/view', 'id' => $id]);
        }

        // Валидация количества
        $quantity = max(1, (int)$quantity);
        
        // Проверка количества на складе (если есть такая логика)
        if ($product->hasStock() && $product->stock < $quantity) {
            $message = "Недостаточно товара на складе. Доступно: {$product->stock} шт.";
            
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => $message,
                ];
            }
            
            Yii::$app->session->setFlash('error', $message);
            return $this->redirect(['product/view', 'id' => $id]);
        }

        // Добавляем товар в корзину
        $cart = Yii::$app->cart;
        $cart->add($id, $quantity);

        // Логирование действия
        Yii::info("Product {$id} added to cart. Quantity: {$quantity}", __METHOD__);

        // AJAX ответ
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $subtotal = $cart->getTotalSum();
            $deliveryThreshold = Yii::$app->params['freeDeliveryThreshold'] ?? 3000;
            $deliveryCost = $subtotal >= $deliveryThreshold ? 0 : (Yii::$app->params['deliveryCost'] ?? 300);
            $total = $subtotal + $deliveryCost;

            return [
                'success' => true,
                'count' => count($cart->getItems()),
                'totalCount' => $cart->getTotalCount(),
                'subtotal' => Yii::$app->formatter->asCurrency($subtotal, 'RUB'),
                'deliveryCost' => $deliveryCost === 0 ? 'Бесплатно' : Yii::$app->formatter->asCurrency($deliveryCost, 'RUB'),
                'total' => Yii::$app->formatter->asCurrency($total, 'RUB'),
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => Yii::$app->formatter->asCurrency($product->price, 'RUB'),
                    'image' => $product->getImageUrl(),
                ],
                'message' => 'Товар успешно добавлен в корзину',
            ];
        }

        // Обычный ответ
        Yii::$app->session->setFlash('success', 'Товар добавлен в корзину');
        return $this->redirect(['index']);
    }

    /**
     * Удаление товара из корзины
     * 
     * @param int $id ID товара
     * @return mixed
     * @throws BadRequestHttpException если некорректные данные
     */
    public function actionRemove($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new BadRequestHttpException('Некорректный ID товара');
        }

        $cart = Yii::$app->cart;
        
        // Проверяем, есть ли товар в корзине
        if (!$cart->hasItem($id)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => 'Товар не найден в корзине',
                ];
            }
            
            Yii::$app->session->setFlash('error', 'Товар не найден в корзине');
            return $this->redirect(['index']);
        }

        $cart->remove($id);

        // Логирование
        Yii::info("Product {$id} removed from cart", __METHOD__);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $subtotal = $cart->getTotalSum();
            $deliveryThreshold = Yii::$app->params['freeDeliveryThreshold'] ?? 3000;
            $deliveryCost = $subtotal >= $deliveryThreshold ? 0 : (Yii::$app->params['deliveryCost'] ?? 300);
            $total = $subtotal + $deliveryCost;

            return [
                'success' => true,
                'count' => count($cart->getItems()),
                'totalCount' => $cart->getTotalCount(),
                'subtotal' => Yii::$app->formatter->asCurrency($subtotal, 'RUB'),
                'deliveryCost' => $deliveryCost === 0 ? 'Бесплатно' : Yii::$app->formatter->asCurrency($deliveryCost, 'RUB'),
                'total' => Yii::$app->formatter->asCurrency($total, 'RUB'),
                'message' => 'Товар удален из корзины',
            ];
        }

        Yii::$app->session->setFlash('success', 'Товар удален из корзины');
        return $this->redirect(['index']);
    }

    /**
     * Очистка корзины
     */
    public function actionClear()
    {
        $cart = Yii::$app->cart;
        $itemsCount = count($cart->getItems());
        
        $cart->clear();

        // Логирование
        Yii::info("Cart cleared. {$itemsCount} items removed", __METHOD__);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => true,
                'message' => 'Корзина очищена',
                'count' => 0,
                'totalCount' => 0,
                'total' => Yii::$app->formatter->asCurrency(0, 'RUB'),
            ];
        }

        Yii::$app->session->setFlash('success', 'Корзина очищена');
        return $this->redirect(['index']);
    }

    /**
     * Обновление количества товара в корзине
     */
    public function actionUpdateQuantity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $id = $request->post('id');
        $quantity = (int)$request->post('quantity', 1);

        // Валидация
        if (!$id || !is_numeric($id) || $id <= 0) {
            return ['success' => false, 'message' => 'Некорректный ID товара'];
        }

        if ($quantity < 1) {
            return ['success' => false, 'message' => 'Количество должно быть больше 0'];
        }

        $product = Product::findOne($id);
        if (!$product) {
            return ['success' => false, 'message' => 'Товар не найден'];
        }

        // Проверка доступности товара
        if (!$product->isAvailable()) {
            return ['success' => false, 'message' => 'Товар недоступен'];
        }

        // Проверка количества на складе
        if ($product->hasStock() && $product->stock < $quantity) {
            return [
                'success' => false, 
                'message' => "Недостаточно товара на складе. Доступно: {$product->stock} шт."
            ];
        }

        $cart = Yii::$app->cart;
        
        // Проверяем, есть ли товар в корзине
        if (!$cart->hasItem($id)) {
            return ['success' => false, 'message' => 'Товар не найден в корзине'];
        }

        $cart->update($id, $quantity);

        // Логирование
        Yii::info("Cart item {$id} quantity updated to {$quantity}", __METHOD__);

        // Расчеты
        $itemSum = $product->price * $quantity;
        $subtotal = $cart->getTotalSum();
        $deliveryThreshold = Yii::$app->params['freeDeliveryThreshold'] ?? 3000;
        $deliveryCost = $subtotal >= $deliveryThreshold ? 0 : (Yii::$app->params['deliveryCost'] ?? 300);
        $total = $subtotal + $deliveryCost;

        return [
            'success' => true,
            'item_sum' => Yii::$app->formatter->asCurrency($itemSum, 'RUB'),
            'subtotal' => Yii::$app->formatter->asCurrency($subtotal, 'RUB'),
            'deliveryCost' => $deliveryCost === 0 ? 'Бесплатно' : Yii::$app->formatter->asCurrency($deliveryCost, 'RUB'),
            'total' => Yii::$app->formatter->asCurrency($total, 'RUB'),
            'count' => count($cart->getItems()),
            'totalCount' => $cart->getTotalCount(),
            'message' => 'Количество товара обновлено',
        ];
    }

    /**
     * Получение содержимого корзины для AJAX
     */
    public function actionGetCartData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cart = Yii::$app->cart;
        $items = $cart->getProductData();
        $subtotal = $cart->getTotalSum();
        $deliveryThreshold = Yii::$app->params['freeDeliveryThreshold'] ?? 3000;
        $deliveryCost = $subtotal >= $deliveryThreshold ? 0 : (Yii::$app->params['deliveryCost'] ?? 300);
        $total = $subtotal + $deliveryCost;

        $cartItems = [];
        foreach ($items as $item) {
            $cartItems[] = [
                'id' => $item['product']->id,
                'name' => $item['product']->name,
                'price' => $item['product']->price,
                'quantity' => $item['quantity'],
                'sum' => $item['sum'],
                'image' => $item['product']->getImageUrl(),
                'url' => \yii\helpers\Url::to(['product/view', 'id' => $item['product']->id]),
            ];
        }

        return [
            'success' => true,
            'items' => $cartItems,
            'count' => count($cart->getItems()),
            'totalCount' => $cart->getTotalCount(),
            'subtotal' => $subtotal,
            'deliveryCost' => $deliveryCost,
            'total' => $total,
            'subtotalFormatted' => Yii::$app->formatter->asCurrency($subtotal, 'RUB'),
            'deliveryCostFormatted' => $deliveryCost === 0 ? 'Бесплатно' : Yii::$app->formatter->asCurrency($deliveryCost, 'RUB'),
            'totalFormatted' => Yii::$app->formatter->asCurrency($total, 'RUB'),
        ];
    }

    /**
     * Переход к оформлению заказа
     */
    public function actionCheckout()
    {
        $cart = Yii::$app->cart;

        if (empty($cart->getItems())) {
            Yii::$app->session->setFlash('error', 'Корзина пуста');
            return $this->redirect(['index']);
        }

        // Проверяем доступность всех товаров в корзине
        $unavailableItems = [];
        foreach ($cart->getItems() as $id => $quantity) {
            $product = Product::findOne($id);
            if (!$product || !$product->isAvailable()) {
                $unavailableItems[] = $product ? $product->name : "Товар #{$id}";
                $cart->remove($id);
            }
        }

        if (!empty($unavailableItems)) {
            $message = 'Следующие товары больше недоступны и были удалены из корзины: ' . implode(', ', $unavailableItems);
            Yii::$app->session->setFlash('warning', $message);
            
            if (empty($cart->getItems())) {
                return $this->redirect(['index']);
            }
        }

        return $this->redirect(['order/create']);
    }
}