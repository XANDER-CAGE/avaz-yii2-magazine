<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Category;

class FooterWidget extends Widget
{
    public function run()
    {
        $categories = Category::find()->limit(5)->all();
        return $this->render('footer', ['categories' => $categories]);
    }
}
