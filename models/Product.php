<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель Product представляет товар в магазине.
 *
 * @property int $id ID товара
 * @property int $category_id ID категории
 * @property string $name Название товара
 * @property string $slug URL-дружественное название
 * @property string $sku Артикул
 * @property float $price Цена
 * @property string $short_description Краткое описание
 * @property string $full_description Полное описание
 * @property string $image Путь к изображению
 * @property bool $status Статус товара (1 - активен, 0 - неактивен)
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property Category $category Категория товара
 */
class Product extends ActiveRecord
{
    /**
     * @var UploadedFile Загружаемое изображение товара
     */
    public $imageFile;

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
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'category_id'], 'required'],
            [['price'], 'number'],
            [['category_id'], 'integer'],
            [['short_description', 'sku', 'full_description', 'slug', 'image'], 'string'],
            [['status'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp', 'skipOnEmpty' => true],
            [['slug'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'name' => 'Название',
            'slug' => 'URL',
            'sku' => 'Артикул',
            'price' => 'Цена',
            'short_description' => 'Краткое описание',
            'full_description' => 'Полное описание',
            'image' => 'Изображение',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'imageFile' => 'Загрузить изображение',
        ];
    }

    /**
     * Загружает изображение товара
     *
     * @return bool Успешность загрузки
     */
    public function uploadImage()
    {
        if ($this->imageFile) {
            $filename = uniqid() . '.' . $this->imageFile->extension;
            $path = \Yii::getAlias('@webroot/uploads/' . $filename);
            if ($this->imageFile->saveAs($path)) {
                $this->image = '/uploads/' . $filename;
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Возвращает категорию товара
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
    
    /**
     * Генерирует уникальный slug для товара
     *
     * @param string $name Название товара для генерации slug
     * @return string Уникальный slug
     */
    public function generateSlug($name = null)
    {
        if ($name === null) {
            $name = $this->name;
        }
        
        $slug = \yii\helpers\Inflector::slug($name);
        $baseSlug = $slug;
        $i = 1;
        
        while (self::find()->where(['slug' => $slug])->andWhere(['!=', 'id', $this->id])->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }
        
        return $slug;
    }

    /**
     * Форматирует цену для отображения
     *
     * @param bool $withCurrency Включать ли символ валюты
     * @return string Форматированная цена
     */
    public function getFormattedPrice($withCurrency = true)
    {
        $price = number_format($this->price, 0, '.', ' ');
        return $withCurrency ? $price . ' ₽' : $price;
    }
}