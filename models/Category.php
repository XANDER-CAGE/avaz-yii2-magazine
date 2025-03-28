<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 *
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * Возвращает топ-5 категорий (по количеству товаров)
     * @param int $limit Количество категорий для возврата
     * @return Category[] Массив объектов категорий
     */
    public static function getTopCategories($limit = 5)
    {
        return self::find()
            ->select(['category.*', 'COUNT(product.id) as productCount'])
            ->leftJoin('product', 'product.category_id = category.id')
            ->groupBy('category.id')
            ->orderBy(['productCount' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

}
