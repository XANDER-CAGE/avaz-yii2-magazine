<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Category;

class CategoryMenuWidget extends Widget
{
    public function run()
    {
        $categories = Category::find()->all();
        return $this->render('category-menu', ['categories' => $categories]);
    }
}
