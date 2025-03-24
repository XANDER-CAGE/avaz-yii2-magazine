<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use yii\httpclient\Client;
use yii\helpers\Json;
use yii\helpers\HtmlPurifier;
use yii\helpers\Html;
use yii\helpers\Inflector;

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
        'size' => 50 // Увеличиваем количество товаров на странице до максимума
    ];
    
    // Хранение категорий Tilda
    private $tildaCategories = [];
    private $categoryMapping = [];
    
    /**
     * Главная страница импорта с доступными действиями
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * Импорт всех товаров из API Tilda с использованием пагинации
     * 
     * @param bool $cleanHtml Очищать HTML-теги в описаниях
     * @param bool $updateExisting Обновлять существующие товары
     * @param bool $importCategories Импортировать категории
     * @return \yii\web\Response
     */
    public function actionImportAllFromApi($cleanHtml = true, $updateExisting = true, $importCategories = true)
    {
        $client = new Client();
        $totalCount = 0;
        $importCount = 0;
        $updateCount = 0;
        $skipCount = 0;
        $errorCount = 0;
        $categoryCount = 0;
        $slice = 1;
        $hasMoreProducts = true;
        
        // Получаем категорию по умолчанию или создаем ее
        $defaultCategory = $this->getOrCreateDefaultCategory();
        if (!$defaultCategory) {
            return $this->redirect(['index']);
        }
        
        // Если нужно импортировать категории, загружаем их предварительно
        if ($importCategories) {
            try {
                $categoryCount = $this->loadTildaCategories();
                Yii::info('Загружено ' . $categoryCount . ' категорий из Tilda', 'import');
            } catch (\Exception $e) {
                Yii::warning('Ошибка при загрузке категорий: ' . $e->getMessage(), 'import');
                Yii::$app->session->setFlash('warning', 'Ошибка при загрузке категорий: ' . $e->getMessage() . '. Будет использована категория по умолчанию.');
            }
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
            
            // При первом запросе загружаем и обрабатываем фильтры (категории) из API
            if ($slice === 1 && $importCategories && !empty($data['filters'])) {
                $this->processTildaFilters($data['filters']);
            }
            
            // Обновляем общее количество товаров
            $totalCount = $data['total'] ?? count($data['products']);
            
            // Обрабатываем полученные товары
            foreach ($data['products'] as $item) {
                $result = $this->processProduct($item, $defaultCategory, $cleanHtml, $updateExisting, $importCategories);
                if ($result === 'imported') {
                    $importCount++;
                } elseif ($result === 'updated') {
                    $updateCount++;
                } elseif ($result === 'skipped') {
                    $skipCount++;
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
        $message = "Импорт завершен: всего товаров - $totalCount, импортировано - $importCount, обновлено - $updateCount, пропущено - $skipCount, с ошибками - $errorCount";
        if ($importCategories) {
            $message .= ". Категорий импортировано/обновлено: " . count($this->categoryMapping);
        }
        
        Yii::$app->session->setFlash('success', $message);
        
        return $this->redirect(['index']);
    }
    
    /**
     * Импорт товаров из API Tilda (только одна страница)
     * 
     * @param bool $cleanHtml Очищать HTML-теги в описаниях
     * @param bool $updateExisting Обновлять существующие товары
     * @param bool $importCategories Импортировать категории
     * @return \yii\web\Response
     */
    public function actionImportFromApi($cleanHtml = true, $updateExisting = true, $importCategories = true)
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
            return $this->redirect(['index']);
        }
        
        try {
            $data = $response->getData();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ошибка при разборе JSON: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
        
        $importCount = 0;
        $updateCount = 0;
        $skipCount = 0;
        $errorCount = 0;
        
        // Получаем категорию по умолчанию или создаем ее
        $defaultCategory = $this->getOrCreateDefaultCategory();
        if (!$defaultCategory) {
            return $this->redirect(['index']);
        }
        
        // Загружаем и обрабатываем категории, если нужно
        if ($importCategories && !empty($data['filters'])) {
            $this->processTildaFilters($data['filters']);
        }
        
        if (isset($data['products']) && !empty($data['products'])) {
            foreach ($data['products'] as $item) {
                $result = $this->processProduct($item, $defaultCategory, $cleanHtml, $updateExisting, $importCategories);
                if ($result === 'imported') {
                    $importCount++;
                } elseif ($result === 'updated') {
                    $updateCount++;
                } elseif ($result === 'skipped') {
                    $skipCount++;
                } else {
                    $errorCount++;
                }
            }
            
            $message = "Импорт завершен: импортировано - $importCount, обновлено - $updateCount, пропущено - $skipCount, с ошибками - $errorCount";
            if ($importCategories) {
                $message .= ". Категорий импортировано/обновлено: " . count($this->categoryMapping);
            }
            
            Yii::$app->session->setFlash('success', $message);
        } else {
            Yii::$app->session->setFlash('warning', 'Товары не найдены в ответе API');
        }
        
        return $this->redirect(['index']);
    }
    
    /**
     * Загружает категории из API Tilda
     * 
     * @return int Количество загруженных категорий
     */
    private function loadTildaCategories()
    {
        // Формируем URL запроса на первую страницу для получения фильтров
        $tempParams = $this->params;
        $tempParams['slice'] = 1;
        $tempParams['size'] = 1; // Достаточно одного товара для получения фильтров
        
        $url = $this->baseUrl . '?' . http_build_query($tempParams);
        
        // Отправляем запрос
        $client = new Client();
        $response = $client->get($url)->send();
        
        if (!$response->isOk) {
            throw new \Exception('Ошибка при получении данных: ' . $response->statusCode);
        }
        
        $data = $response->getData();
        
        if (!isset($data['filters']) || !isset($data['filters']['filters'])) {
            throw new \Exception('В ответе API отсутствуют фильтры');
        }
        
        return $this->processTildaFilters($data['filters']);
    }
    
    /**
     * Обрабатывает фильтры из API Tilda и создает соответствующие категории
     * 
     * @param array $filters Фильтры из API Tilda
     * @return int Количество обработанных категорий
     */
    private function processTildaFilters($filters)
    {
        if (!isset($filters['filters'])) {
            return 0;
        }
        
        $count = 0;
        
        foreach ($filters['filters'] as $filter) {
            // Ищем фильтр категорий (storepartuid)
            if ($filter['name'] === 'storepartuid' && isset($filter['values']) && is_array($filter['values'])) {
                $this->tildaCategories = $filter['values'];
                
                // Создаем/обновляем категории в базе данных
                foreach ($filter['values'] as $category) {
                    if (!isset($category['id']) || !isset($category['value'])) {
                        continue;
                    }
                    
                    $categoryId = $category['id'];
                    $categoryName = $category['value'];
                    $categoryCount = $category['count'] ?? 0;
                    
                    // Проверяем существование категории по имени
                    $existingCategory = Category::findOne(['name' => $categoryName]);
                    
                    if ($existingCategory) {
                        // Сохраняем соответствие Tilda ID и локального ID
                        $this->categoryMapping[$categoryId] = $existingCategory->id;
                    } else {
                        // Создаем новую категорию
                        $newCategory = new Category();
                        $newCategory->name = $categoryName;
                        $newCategory->slug = Inflector::slug($categoryName);
                        $newCategory->status = 1; // Активная категория
                        
                        if ($newCategory->save()) {
                            $this->categoryMapping[$categoryId] = $newCategory->id;
                            $count++;
                        } else {
                            Yii::error('Ошибка при создании категории: ' . Json::encode($newCategory->errors), 'import');
                        }
                    }
                }
                
                break; // Нашли нужный фильтр, выходим из цикла
            }
        }
        
        return $count;
    }
    
    /**
     * Очистка всех импортированных товаров из Tilda
     */
    public function actionClearImported()
    {
        try {
            // Поиск товаров с SKU, начинающимся с 'tilda_'
            $count = Product::deleteAll(['like', 'sku', 'tilda_%', false]);
            
            // Сбрасываем автоинкремент, если нужно
            if ($count > 0) {
                // Получаем текущий максимальный ID
                $maxId = Product::find()->max('id');
                if ($maxId) {
                    // Устанавливаем новое значение автоинкремента
                    Yii::$app->db->createCommand('ALTER TABLE product AUTO_INCREMENT = ' . ($maxId + 1))->execute();
                }
            }
            
            Yii::$app->session->setFlash('success', "Удалено $count товаров, импортированных из Tilda");
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении товаров: ' . $e->getMessage());
        }
        
        return $this->redirect(['index']);
    }
    
    /**
     * Обрабатывает один товар из Tilda
     *
     * @param array $item Данные товара
     * @param Category $defaultCategory Категория по умолчанию
     * @param bool $cleanHtml Очищать HTML-теги в описаниях
     * @param bool $updateExisting Обновлять существующие товары
     * @param bool $importCategories Импортировать категории
     * @return string Результат обработки ('imported', 'updated', 'skipped', 'error')
     */
    private function processProduct($item, $defaultCategory, $cleanHtml = true, $updateExisting = true, $importCategories = true)
    {
        // Проверка обязательных полей
        if (empty($item['title']) || !isset($item['uid'])) {
            Yii::warning('Товар пропущен из-за отсутствия обязательных полей: ' . Json::encode($item));
            return 'error';
        }
        
        // Формируем уникальный SKU для товара Tilda
        $tildaSku = 'tilda_' . $item['uid'];
        
        // Проверяем, существует ли товар с таким SKU
        $existingProduct = Product::findOne(['sku' => $tildaSku]);
        
        // Если товар существует и не нужно обновлять - пропускаем
        if ($existingProduct && !$updateExisting) {
            return 'skipped';
        }
        
        // Создаём новый объект Product или используем существующий
        $product = $existingProduct ?? new Product();
        $isNewProduct = $product->isNewRecord;
        
        // Очищаем от HTML-тегов, если нужно
        $title = $item['title'];
        $description = $item['descr'] ?? '';
        $text = $item['text'] ?? '';
        
        if ($cleanHtml) {
            $title = strip_tags($title);
            $description = strip_tags($description);
            $text = HtmlPurifier::process($text); // Безопасная очистка HTML с помощью HtmlPurifier
        }
        
        // Заполняем поля
        $product->name = $title;
        $product->sku = $tildaSku;
        $product->price = $item['price'] ?? 0;
        $product->short_description = $description;
        $product->full_description = $text;
        $product->status = 1; // Активен по умолчанию
        
        // Определяем категорию товара
        $categoryId = $this->getCategoryIdFromPartuids($item, $defaultCategory->id, $importCategories);
        $product->category_id = $categoryId;
        
        // Генерируем slug, если его нет
        if (empty($product->slug)) {
            $product->slug = $this->generateSlug($product->name);
        }
        
        // Обрабатываем изображение
        if (!empty($item['gallery']) && (empty($product->image) || $updateExisting)) {
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
            return $isNewProduct ? 'imported' : 'updated';
        } else {
            Yii::error('Не удалось сохранить товар: ' . Json::encode([
                'errors' => $product->errors,
                'data' => $item
            ]));
            return 'error';
        }
    }
    
    /**
     * Определяет категорию товара на основе partuids из Tilda
     *
     * @param array $item Данные товара
     * @param int $defaultCategoryId ID категории по умолчанию
     * @param bool $importCategories Импортировать категории
     * @return int ID категории
     */
    private function getCategoryIdFromPartuids($item, $defaultCategoryId, $importCategories = true)
    {
        // Если импорт категорий отключен или нет маппинга категорий
        if (!$importCategories || empty($this->categoryMapping)) {
            return $defaultCategoryId;
        }
        
        // Если у товара нет partuids
        if (empty($item['partuids'])) {
            return $defaultCategoryId;
        }
        
        try {
            // Парсим partuids - это может быть как JSON строка, так и уже декодированный массив
            $partuids = $item['partuids'];
            if (is_string($partuids)) {
                $partuids = Json::decode($partuids);
            }
            
            // Если не массив или пустой массив
            if (!is_array($partuids) || empty($partuids)) {
                return $defaultCategoryId;
            }
            
            // Ищем первую доступную категорию из partuids в нашем маппинге
            foreach ($partuids as $partuid) {
                if (isset($this->categoryMapping[$partuid])) {
                    return $this->categoryMapping[$partuid];
                }
            }
            
            // Если необходимо создать новую категорию для partuid
            if (!empty($this->tildaCategories) && $importCategories) {
                foreach ($this->tildaCategories as $tildaCategory) {
                    if (isset($tildaCategory['id']) && in_array($tildaCategory['id'], $partuids)) {
                        // Создаем новую категорию
                        $newCategory = new Category();
                        $newCategory->name = $tildaCategory['value'];
                        $newCategory->slug = Inflector::slug($tildaCategory['value']);
                        $newCategory->status = 1;
                        
                        if ($newCategory->save()) {
                            $this->categoryMapping[$tildaCategory['id']] = $newCategory->id;
                            return $newCategory->id;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Yii::warning('Ошибка при обработке partuids: ' . $e->getMessage(), 'import');
        }
        
        return $defaultCategoryId;
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
            $defaultCategory->slug = 'import-iz-tilda';
            $defaultCategory->status = 1;
            
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
        $slug = Inflector::slug($name);
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
        
        // Выводим информацию о фильтрах (категориях)
        if (isset($data['filters']) && isset($data['filters']['filters'])) {
            $html .= "<h2>Фильтры (категории):</h2>";
            foreach ($data['filters']['filters'] as $filter) {
                if ($filter['name'] === 'storepartuid') {
                    $html .= "<pre>" . htmlspecialchars(Json::encode($filter, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
                    break;
                }
            }
        }
        
        if (isset($data['products']) && !empty($data['products'])) {
            $firstProduct = $data['products'][0];
            $html .= "<h2>Структура первого товара:</h2>";
            $html .= "<pre>" . htmlspecialchars(Json::encode($firstProduct, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
            
            // Анализируем partuids
            if (isset($firstProduct['partuids'])) {
                $html .= "<h3>Partuids товара:</h3>";
                try {
                    $partuids = $firstProduct['partuids'];
                    if (is_string($partuids)) {
                        $partuids = Json::decode($partuids);
                    }
                    $html .= "<pre>" . htmlspecialchars(Json::encode($partuids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
                    
                    // Показываем соответствие partuids и категорий
                    if (isset($data['filters']) && isset($data['filters']['filters'])) {
                        foreach ($data['filters']['filters'] as $filter) {
                            if ($filter['name'] === 'storepartuid' && isset($filter['values'])) {
                                $html .= "<h3>Соответствие partuids и категорий:</h3>";
                                $html .= "<table border='1' cellpadding='5'>";
                                $html .= "<tr><th>ID категории</th><th>Название категории</th><th>Присутствует в товаре</th></tr>";
                                
                                foreach ($filter['values'] as $category) {
                                    if (isset($category['id']) && isset($category['value'])) {
                                        $isInProduct = in_array($category['id'], $partuids) ? 'Да' : 'Нет';
                                        $html .= "<tr>";
                                        $html .= "<td>" . htmlspecialchars($category['id']) . "</td>";
                                        $html .= "<td>" . htmlspecialchars($category['value']) . "</td>";
                                        $html .= "<td>" . $isInProduct . "</td>";
                                        $html .= "</tr>";
                                    }
                                }
                                
                                $html .= "</table>";
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $html .= "<p>Ошибка при разборе partuids: " . $e->getMessage() . "</p>";
                }
            }
            
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