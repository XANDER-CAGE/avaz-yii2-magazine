<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use yii\httpclient\Client;

class ProductImportController extends Controller
{
    public function actionImportFromApi()
    {
        // Указываем URL для API
        $url = 'https://store.tildaapi.com/api/getproductslist/?storepartuid=403921693082&recid=230354419&c=1742742864790&slice=2&getparts=true&size=36';

        // Получаем данные с API
        $client = new Client();
        $response = $client->get($url)->send();
        $data = $response->getData();

        if (isset($data['products']) && !empty($data['products'])) {
            foreach ($data['products'] as $item) {
                // Создаём новый объект Product
                $product = new Product();

                // Заполняем поля
                $product->name = $item['title'];
                $product->sku = $item['sku'] ?? '';
                $product->price = $item['price'];
                $product->short_description = $item['descr'];
                $product->full_description = $item['text'];
                $product->status = 1; // Активен по умолчанию

                // Сохраняем изображение
                $images = json_decode($item['gallery'], true);
                $product->image = $images[0]['img'] ?? null; // Загружаем первое изображение

                // Сохраняем товар в базе
                if (!$product->save()) {
                    Yii::error('Не удалось сохранить товар: ' . json_encode($product->errors));
                }
            }

            Yii::$app->session->setFlash('success', 'Товары успешно импортированы!');
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось загрузить данные с API');
        }

        return $this->redirect(['index']);
    }
}
