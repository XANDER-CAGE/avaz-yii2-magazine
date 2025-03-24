<?php

namespace app\widgets;

use yii\base\Widget;

class MainMenuWidget extends Widget
{
    public function run()
    {
        return $this->render('main-menu');
    }
}
