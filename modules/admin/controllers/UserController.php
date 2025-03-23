<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;

class UserController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->all();
        return $this->render('index', ['users' => $users]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь создан');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = User::findOne($id);
        if (!$model) throw new NotFoundHttpException("Пользователь не найден");

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password_hash)) {
                $model->setPassword($model->password_hash);
            } else {
                $model->password_hash = $model->getOldAttribute('password_hash');
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь обновлён');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить самого себя');
        } else {
            User::deleteAll(['id' => $id]);
            Yii::$app->session->setFlash('success', 'Пользователь удалён');
        }
        return $this->redirect(['index']);
    }
}
