<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $full_description
 * @property string|null $sku
 * @property float $price
 * @property float|null $old_price
 * @property string|null $image
 * @property int $category_id
 * @property int $status
 * @property int|null $stock
 * @property float|null $weight
 * @property string|null $dimensions
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int $views
 * @property int $sales_count
 * @property float|null $rating
 * @property int $reviews_count
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $category
 * @property OrderItem[] $orderItems
 * @property Review[] $reviews
 */
class Product extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_OUT_OF_STOCK = 2;
    const STATUS_DISCONTINUED = 3;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'category_id'], 'required'],
            [['description', 'full_description', 'meta_description'], 'string'],
            [['price', 'old_price', 'weight', 'rating'], 'number', 'min' => 0],
            [['category_id', 'status', '', 'views', 'sales_count', 'reviews_count'], 'integer', 'min' => 0],
            [['name', 'sku', 'image', 'dimensions', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['views', 'sales_count', 'reviews_count'], 'default', 'value' => 0],
            [['rating'], 'default', 'value' => 0],
            [[], 'default', 'value' => null],
            [['sku'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            
            // Сценарий создания
            [['name', 'price'], 'required', 'on' => self::SCENARIO_CREATE],
            
            // Сценарий обновления
            [['name', 'price'], 'required', 'on' => self::SCENARIO_UPDATE],
            
            // Валидация изображения (если используется загрузка файлов)
            [['image'], 'string'],
            
            // Валидация цен
            [['old_price'], 'compare', 'compareAttribute' => 'price', 'operator' => '>=', 'when' => function($model) {
                return !empty($model->old_price);
            }, 'message' => 'Старая цена должна быть больше или равна текущей цене'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Краткое описание',
            'full_description' => 'Полное описание',
            'sku' => 'Артикул',
            'price' => 'Цена',
            'old_price' => 'Старая цена',
            'image' => 'Изображение',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'stock' => 'Количество на складе',
            'weight' => 'Вес (кг)',
            'dimensions' => 'Размеры',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'views' => 'Просмотры',
            'sales_count' => 'Количество продаж',
            'rating' => 'Рейтинг',
            'reviews_count' => 'Количество отзывов',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Связь с категорией
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Связь с элементами заказов
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'id']);
    }

    /**
     * Связь с отзывами
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['product_id' => 'id']);
    }

    /**
     * Связь с активными отзывами
     */
    public function getActiveReviews()
    {
        return $this->hasMany(Review::class, ['product_id' => 'id'])
            ->where(['status' => Review::STATUS_APPROVED])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * Получить статусы
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_INACTIVE => 'Неактивен',
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_OUT_OF_STOCK => 'Нет в наличии',
            self::STATUS_DISCONTINUED => 'Снят с производства',
        ];
    }

    /**
     * Получить название статуса
     */
    public function getStatusName()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Неизвестно';
    }

    /**
     * Получить CSS класс для статуса
     */
    public function getStatusClass()
    {
        $classes = [
            self::STATUS_INACTIVE => 'secondary',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_OUT_OF_STOCK => 'warning',
            self::STATUS_DISCONTINUED => 'danger',
        ];
        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Проверить, доступен ли товар для покупки
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_ACTIVE && 
               (!$this->hasStock() || $this->stock > 0);
    }

    /**
     * Проверить, отслеживается ли остаток товара
     */
    public function hasStock()
    {
        return $this->stock !== null;
    }

    /**
     * Проверить, есть ли товар в наличии
     */
    public function inStock()
    {
        return !$this->hasStock() || $this->stock > 0;
    }

    /**
     * Получить URL изображения
     */
    public function getImageUrl($defaultImage = '/img/no-image.jpg')
    {
        if (empty($this->image)) {
            return $defaultImage;
        }

        // Если изображение уже содержит полный URL
        if (strpos($this->image, 'http') === 0) {
            return $this->image;
        }

        // Если изображение в папке uploads
        if (strpos($this->image, '/') === 0) {
            return $this->image;
        }

        // Добавляем префикс для изображений
        return '/uploads/products/' . $this->image;
    }

    /**
     * Получить миниатюру изображения
     */
    public function getThumbnailUrl($size = '300x300', $defaultImage = '/img/no-image.jpg')
    {
        $imageUrl = $this->getImageUrl($defaultImage);
        
        // Здесь можно добавить логику генерации миниатюр
        // Например, используя библиотеку для изменения размера изображений
        
        return $imageUrl;
    }

    /**
     * Получить форматированную цену
     */
    public function getFormattedPrice()
    {
        return Yii::$app->formatter->asCurrency($this->price, 'RUB');
    }

    /**
     * Получить форматированную старую цену
     */
    public function getFormattedOldPrice()
    {
        if (empty($this->old_price)) {
            return null;
        }
        return Yii::$app->formatter->asCurrency($this->old_price, 'RUB');
    }

    /**
     * Проверить, есть ли скидка
     */
    public function isOnSale()
    {
        return !empty($this->old_price) && $this->old_price > $this->price;
    }

    /**
     * Получить размер скидки в процентах
     */
    public function getDiscountPercent()
    {
        if (!$this->isOnSale()) {
            return 0;
        }
        
        return round((($this->old_price - $this->price) / $this->old_price) * 100);
    }

    /**
     * Получить размер скидки в рублях
     */
    public function getDiscountAmount()
    {
        if (!$this->isOnSale()) {
            return 0;
        }
        
        return $this->old_price - $this->price;
    }

    /**
     * Получить URL товара
     */
    public function getUrl()
    {
        return Url::to(['product/view', 'id' => $this->id]);
    }

    /**
     * Получить SEO-заголовок
     */
    public function getSeoTitle()
    {
        return !empty($this->meta_title) ? $this->meta_title : $this->name;
    }

    /**
     * Получить SEO-описание
     */
    public function getSeoDescription()
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }
        
        if (!empty($this->description)) {
            return Html::encode(strip_tags($this->description));
        }
        
        return 'Купить ' . $this->name . ' в интернет-магазине с доставкой по России';
    }

    /**
     * Получить рейтинг в звездах (массив true/false для 5 звезд)
     */
    public function getStars()
    {
        $stars = [];
        $rating = round($this->rating);
        
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = $i <= $rating;
        }
        
        return $stars;
    }

    /**
     * Получить среднюю оценку
     */
    public function getAverageRating()
    {
        if ($this->reviews_count == 0) {
            return 0;
        }
        
        return round($this->rating, 1);
    }

    /**
     * Проверить, новый ли товар (создан за последние 30 дней)
     */
    public function isNew($days = 30)
    {
        return $this->created_at > (time() - ($days * 24 * 60 * 60));
    }

    /**
     * Проверить, популярный ли товар
     */
    public function isPopular($minSales = 10)
    {
        return $this->sales_count >= $minSales;
    }

    /**
     * Проверить, хит ли продаж
     */
    public function isBestseller($minRating = 4.0, $minReviews = 5)
    {
        return $this->rating >= $minRating && $this->reviews_count >= $minReviews;
    }

    /**
     * Увеличить счетчик просмотров
     */
    public function incrementViews()
    {
        $this->updateCounters(['views' => 1]);
    }

    /**
     * Увеличить счетчик продаж
     */
    public function incrementSales($quantity = 1)
    {
        $this->updateCounters(['sales_count' => $quantity]);
    }

    /**
     * Обновить рейтинг товара
     */
    public function updateRating()
    {
        $reviews = $this->getActiveReviews()->all();
        
        if (empty($reviews)) {
            $this->rating = 0;
            $this->reviews_count = 0;
        } else {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $this->rating = $totalRating / count($reviews);
            $this->reviews_count = count($reviews);
        }
        
        $this->save(false, ['rating', 'reviews_count']);
    }

    /**
     * Уменьшить остаток товара
     */
    public function decreaseStock($quantity)
    {
        if ($this->hasStock() && $this->stock >= $quantity) {
            $this->updateCounters(['stock' => -$quantity]);
            return true;
        }
        return false;
    }

    /**
     * Увеличить остаток товара
     */
    public function increaseStock($quantity)
    {
        if ($this->hasStock()) {
            $this->updateCounters(['stock' => $quantity]);
            return true;
        }
        return false;
    }

    /**
     * Получить похожие товары
     */
    public function getSimilarProducts($limit = 4)
    {
        return self::find()
            ->where(['category_id' => $this->category_id])
            ->andWhere(['!=', 'id', $this->id])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->orderBy(['sales_count' => SORT_DESC, 'rating' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Поиск товаров
     */
    public static function search($query, $categoryId = null, $limit = 20)
    {
        $searchQuery = self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['like', 'name', $query])
            ->orWhere(['like', 'description', $query])
            ->orWhere(['like', 'sku', $query]);

        if ($categoryId) {
            $searchQuery->andWhere(['category_id' => $categoryId]);
        }

        return $searchQuery
            ->orderBy(['sales_count' => SORT_DESC, 'rating' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Получить товары категории
     */
    public static function getByCategory($categoryId, $limit = null, $orderBy = null)
    {
        $query = self::find()
            ->where(['category_id' => $categoryId])
            ->andWhere(['status' => self::STATUS_ACTIVE]);

        if ($orderBy) {
            $query->orderBy($orderBy);
        } else {
            $query->orderBy(['created_at' => SORT_DESC]);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->all();
    }

    /**
     * Получить популярные товары
     */
    public static function getPopular($limit = 10)
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->orderBy(['sales_count' => SORT_DESC, 'views' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Получить новые товары
     */
    public static function getNew($limit = 10, $days = 30)
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['>=', 'created_at', time() - ($days * 24 * 60 - 60)])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Получить товары со скидкой
     */
    public static function getOnSale($limit = 10)
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['>', 'old_price', 0])
            ->andWhere('old_price > price')
            ->orderBy(['(old_price - price) / old_price' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Валидация перед сохранением
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Автоматическое заполнение SEO полей
        if (empty($this->meta_title)) {
            $this->meta_title = $this->name;
        }

        if (empty($this->meta_description) && !empty($this->description)) {
            $this->meta_description = Html::encode(strip_tags($this->description));
            if (mb_strlen($this->meta_description) > 160) {
                $this->meta_description = mb_substr($this->meta_description, 0, 157) . '...';
            }
        }

        // Генерация артикула, если не указан
        if (empty($this->sku)) {
            $this->sku = 'PROD-' . strtoupper(substr(md5($this->name . time()), 0, 8));
        }

        return true;
    }

    /**
     * Действия после сохранения
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Очистка кеша при обновлении товара
        if (!$insert) {
            Yii::$app->cache->delete("product_{$this->id}");
            Yii::$app->cache->delete("category_{$this->category_id}_products");
        }
    }

    /**
     * Действия после удаления
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // Очистка кеша
        Yii::$app->cache->delete("product_{$this->id}");
        Yii::$app->cache->delete("category_{$this->category_id}_products");

        // Удаление изображения (если нужно)
        if (!empty($this->image) && file_exists(Yii::getAlias('@webroot') . $this->image)) {
            @unlink(Yii::getAlias('@webroot') . $this->image);
        }
    }
}