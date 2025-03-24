<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class HeaderWidget extends Widget
{
    public function run()
    {
        return $this->render('header');
    }
}
