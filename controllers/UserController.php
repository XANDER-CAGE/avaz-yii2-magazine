<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\models\User;
use app\models\ProfileForm;
use app\models\ChangePasswordForm;
use yii\web\NotFoundHttpException;

class UserController extends Controller
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
                        'roles' => ['@'], // Только для авторизованных пользователей
                    ],
                ],
            ],
        ];
    }

    /**
     * Страница профиля пользователя
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        
        // Получаем последние 5 заказов пользователя
        $orders = \app\models\Order::find()
            ->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('profile', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Редактирование профиля
     */
    public function actionEditProfile()
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
                return $this->redirect(['profile']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при обновлении профиля.');
            }
        }
        
        return $this->render('edit-profile', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Изменение пароля
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Пароль успешно изменен.');
            return $this->redirect(['profile']);
        }
        
        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление аккаунта
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

    /**
     * Просмотр истории заказов
     */
    public function actionOrderHistory()
    {
        $query = \app\models\Order::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('order-history', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр деталей конкретного заказа
     * 
     * @param int $id ID заказа
     */
    public function actionOrderView($id)
    {
        $order = \app\models\Order::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id
        ]);

        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден.');
        }

        return $this->render('order-view', [
            'order' => $order,
        ]);
    }
}