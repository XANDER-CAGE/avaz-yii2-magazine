<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use yii\httpclient\Client;
use yii\helpers\Json;

class ProductImportController extends Controller
{
    // Базовый URL API Tilda
    private $baseUrl = 'https://store.tildaapi.com/api/getproductslist/';
    
    // Параметры запроса
    private $params = [
        'storepartuid' => '403921693082',
        'recid' => '230354419',
        'c' => '1742742864790',
        'getparts' => 'true',
        'size' => 4 // Увеличиваем количество товаров на странице до максимума
    ];
    
    /**
     * Импорт всех товаров из API Tilda с использованием пагинации
     */
    public function actionImportAllFromApi()
    {
        $client = new Client();
        $totalCount = 0;
        $importCount = 0;
        $errorCount = 0;
        $slice = 1;
        $hasMoreProducts = true;
        
        // Получаем категорию по умолчанию или создаем ее
        $defaultCategory = $this->getOrCreateDefaultCategory();
        if (!$defaultCategory) {
            return $this->redirect(['/admin/product']);
        }
        
        // Цикл по всем страницам с товарами
        while ($hasMoreProducts) {
            // Обновляем номер страницы
            $this->params['slice'] = $slice;
            
            // Формируем URL запроса
            $url = $this->baseUrl . '?' . http_build_query($this->params);
            
            // Отправляем запрос
            $response = $client->get($url)->send();
            
            // Проверяем, что ответ получен
            if (!$response->isOk) {
                Yii::$app->session->setFlash('error', 'Ошибка при получении данных: ' . $response->statusCode);
                break;
            }
            
            try {
                $data = $response->getData();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Ошибка при разборе JSON: ' . $e->getMessage());
                break;
            }
            
            // Проверяем, есть ли товары в ответе
            if (!isset($data['products']) || empty($data['products'])) {
                $hasMoreProducts = false;
                continue;
            }
            
            // Обновляем общее количество товаров
            $totalCount = $data['total'] ?? 0;
            
            // Обрабатываем полученные товары
            foreach ($data['products'] as $item) {
                $result = $this->processProduct($item, $defaultCategory);
                if ($result) {
                    $importCount++;
                } else {
                    $errorCount++;
                }
            }
            
            // Проверяем, есть ли еще товары
            $productsOnPage = count($data['products']);
            if ($productsOnPage < $this->params['size']) {
                $hasMoreProducts = false;
            } else {
                $slice++;
                // Небольшая пауза между запросами, чтобы не перегружать API
                sleep(1);
            }
        }
        
        // Устанавливаем flash-сообщение с результатами импорта
        Yii::$app->session->setFlash(
            'success', 
            "Импорт завершен: всего товаров - $totalCount, импортировано - $importCount, с ошибками - $errorCount"
        );
        
        return $this->redirect(['/admin/product']);
    }
    
    /**
     * Импорт товаров из API Tilda (только одна страница)
     */
    public function actionImportFromApi()
    {
        // Задаем номер страницы
        $this->params['slice'] = 1;
        
        // Формируем URL запроса
        $url = $this->baseUrl . '?' . http_build_query($this->params);
        
        // Отправляем запрос
        $client = new Client();
        $response = $client->get($url)->send();
        
        // Проверяем, что ответ получен
        if (!$response->isOk) {
            Yii::$app->session->setFlash('error', 'Ошибка при получении данных: ' . $response->statusCode);
            return $this->redirect(['/admin/product']);
        }
        
        try {
            $data = $response->getData();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ошибка при разборе JSON: ' . $e->getMessage());
            return $this->redirect(['/admin/product']);
        }
        
        $importCount = 0;
        $errorCount = 0;
        
        // Получаем категорию по умолчанию или создаем ее
        $defaultCategory = $this->getOrCreateDefaultCategory();
        if (!$defaultCategory) {
            return $this->redirect(['/admin/product']);
        }
        
        if (isset($data['products']) && !empty($data['products'])) {
            foreach ($data['products'] as $item) {
                $result = $this->processProduct($item, $defaultCategory);
                if ($result) {
                    $importCount++;
                } else {
                    $errorCount++;
                }
            }
            
            Yii::$app->session->setFlash(
                'success', 
                "Товары импортированы: $importCount успешно, $errorCount с ошибками"
            );
        } else {
            Yii::$app->session->setFlash('warning', 'Товары не найдены в ответе API');
        }
        
        return $this->redirect(['/admin/product']);
    }
    
    /**
     * Обрабатывает один товар из Tilda
     *
     * @param array $item Данные товара
     * @param Category $defaultCategory Категория по умолчанию
     * @return bool Результат обработки
     */
    private function processProduct($item, $defaultCategory)
    {
        // Проверка обязательных полей
        if (empty($item['title']) || !isset($item['price'])) {
            Yii::warning('Товар пропущен из-за отсутствия обязательных полей: ' . Json::encode($item));
            return false;
        }
        
        // Проверяем, существует ли товар с таким UID или SKU
        $existingProduct = null;
        if (!empty($item['uid'])) {
            $existingProduct = Product::findOne(['sku' => 'tilda_' . $item['uid']]);
        } elseif (!empty($item['sku'])) {
            $existingProduct = Product::findOne(['sku' => $item['sku']]);
        }
        
        // Создаём новый объект Product или используем существующий
        $product = $existingProduct ?? new Product();
        
        // Заполняем поля
        $product->name = $item['title'];
        $product->sku = !empty($item['uid']) ? 'tilda_' . $item['uid'] : ($item['sku'] ?? '');
        $product->price = $item['price'];
        $product->short_description = $item['descr'] ?? '';
        $product->full_description = $item['text'] ?? '';
        $product->status = 1; // Активен по умолчанию
        $product->category_id = $defaultCategory->id;
        
        // Генерируем slug, если его нет
        if (empty($product->slug)) {
            $product->slug = $this->generateSlug($product->name);
        }
        
        // Обрабатываем изображение
        if (!empty($item['gallery']) && empty($product->image)) {
            try {
                $galleries = Json::decode($item['gallery']);
                if (is_array($galleries) && !empty($galleries[0]['img'])) {
                    $product->image = $galleries[0]['img'];
                }
            } catch (\Exception $e) {
                Yii::warning('Ошибка при разборе JSON галереи: ' . $e->getMessage());
            }
        }
        
        // Сохраняем товар в базе
        if ($product->save()) {
            return true;
        } else {
            Yii::error('Не удалось сохранить товар: ' . Json::encode([
                'errors' => $product->errors,
                'data' => $item
            ]));
            return false;
        }
    }
    
    /**
     * Получает или создает категорию по умолчанию
     *
     * @return Category|null Категория по умолчанию или null в случае ошибки
     */
    private function getOrCreateDefaultCategory()
    {
        $defaultCategory = Category::findOne(['name' => 'Импорт из Tilda']);
        if (!$defaultCategory) {
            $defaultCategory = new Category();
            $defaultCategory->name = 'Импорт из Tilda';
            
            if (!$defaultCategory->save()) {
                Yii::error('Не удалось создать категорию: ' . Json::encode($defaultCategory->errors));
                Yii::$app->session->setFlash('error', 'Не удалось создать категорию для импорта');
                return null;
            }
        }
        
        return $defaultCategory;
    }
    
    /**
     * Генерирует уникальный slug для товара
     *
     * @param string $name Название товара
     * @return string Уникальный slug
     */
    private function generateSlug($name)
    {
        $slug = \yii\helpers\Inflector::slug($name);
        $baseSlug = $slug;
        $i = 1;
        
        while (Product::find()->where(['slug' => $slug])->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }
        
        return $slug;
    }
    
    /**
     * Вывести информацию о структуре данных API
     * Полезно для отладки
     */
    public function actionDebugApi()
    {
        // Задаем параметры для получения только 1 товара
        $this->params['slice'] = 1;
        $this->params['size'] = 1;
        
        // Формируем URL запроса
        $url = $this->baseUrl . '?' . http_build_query($this->params);
        
        // Отправляем запрос
        $client = new Client();
        $response = $client->get($url)->send();
        
        if (!$response->isOk) {
            return $this->renderContent('Ошибка при получении данных: ' . $response->statusCode);
        }
        
        try {
            $data = $response->getData();
        } catch (\Exception $e) {
            return $this->renderContent('Ошибка при разборе JSON: ' . $e->getMessage());
        }
        
        // Отображаем общую информацию о результатах
        $html = "<h1>Отладочная информация API Tilda</h1>";
        $html .= "<p>URL запроса: " . htmlspecialchars($url) . "</p>";
        
        if (isset($data['total'])) {
            $html .= "<p>Всего товаров: " . $data['total'] . "</p>";
        }
        
        if (isset($data['products']) && !empty($data['products'])) {
            $firstProduct = $data['products'][0];
            $html .= "<h2>Структура первого товара:</h2>";
            $html .= "<pre>" . htmlspecialchars(Json::encode($firstProduct, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
            
            // Анализируем структуру
            $structure = $this->analyzeStructure($firstProduct);
            $html .= "<h2>Анализ структуры:</h2>";
            $html .= "<pre>" . htmlspecialchars(Json::encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
        } else {
            $html .= "<p>Товары не найдены в ответе API</p>";
        }
        
        return $this->renderContent($html);
    }
    
    /**
     * Анализирует структуру данных объекта
     * 
     * @param mixed $data Данные для анализа
     * @return array Структура данных с типами
     */
    private function analyzeStructure($data) 
    {
        $result = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                try {
                    // Проверяем, может ли это быть JSON-строкой
                    if (is_string($value)) {
                        $decodedValue = Json::decode($value);
                        $result[$key] = [
                            'type' => 'json',
                            'structure' => $this->analyzeStructure($decodedValue)
                        ];
                    } else {
                        $result[$key] = [
                            'type' => 'array',
                            'example' => count($value) > 0 ? $value[0] : null
                        ];
                    }
                } catch (\Exception $e) {
                    $result[$key] = [
                        'type' => 'array',
                        'example' => count($value) > 0 ? $value[0] : null
                    ];
                }
            } else {
                $result[$key] = [
                    'type' => gettype($value),
                    'value' => $value,
                ];
            }
        }
        
        return $result;
    }
}