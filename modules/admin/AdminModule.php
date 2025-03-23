<?php

namespace app\modules\admin;

use Yii;
/**
 * admin module definition class
 */
class AdminModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    
        \Yii::$app->layout = '@app/modules/admin/views/layouts/main';

    }
    

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->is_admin) {
            Yii::$app->user->loginRequired();
            return false;
        }

        return parent::beforeAction($action);
    }

}
