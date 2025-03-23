<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\ProfileForm;
use app\models\ChangePasswordForm;
use yii\web\NotFoundHttpException;

/**
 * ProfileController реализует функционал работы с профилем пользователя
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete-account' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Отображает профиль текущего пользователя
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        
        return $this->render('index', [
            'user' => $user,
        ]);
    }

    /**
     * Редактирование профиля
     *
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $user = Yii::$app->user->identity;
        $model = new ProfileForm();
        
        // Заполняем модель текущими данными пользователя
        $model->first_name = $user->first_name;
        $model->last_name = $user->last_name;
        $model->email = $user->email;
        $model->phone = $user->phone;
        
        if ($model->load(Yii::$app->request->post())) {
            // Обрабатываем загрузку аватара
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');
            
            if ($model->update($user)) {
                Yii::$app->session->setFlash('success', 'Профиль успешно обновлен.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при обновлении профиля.');
            }
        }
        
        return $this->render('edit', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Изменение пароля
     *
     * @return string|\yii\web\Response
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Пароль успешно изменен.');
            return $this->redirect(['index']);
        }
        
        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление аккаунта
     *
     * @return \yii\web\Response
     */
    public function actionDeleteAccount()
    {
        $user = Yii::$app->user->identity;
        
        // Логически удаляем аккаунт (меняем статус)
        $user->status = User::STATUS_INACTIVE;
        $user->save(false);
        
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'Ваш аккаунт был успешно удален.');
        
        return $this->goHome();
    }
}