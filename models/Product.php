<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Product extends ActiveRecord
{
    public $imageFile;

    public function rules()
    {
        return [
            [['name', 'price', 'category_id'], 'required'],
            [['price'], 'number'],
            [['category_id'], 'integer'],
            [['short_description', 'sku', 'full_description', 'slug', 'image'], 'string'],
            [['status'], 'boolean'],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp', 'skipOnEmpty' => true],
        ];
    }

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

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}
