<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\modules\admin\models\UserForm;
use app\modules\admin\models\UserSearch;

/**
 * UserController реализует CRUD для управления пользователями
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'batch-action' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Список пользователей
     * 
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр пользователя
     * 
     * @param int $id ID пользователя
     * @return string
     * @throws NotFoundHttpException если пользователь не найден
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Получение заказов пользователя
        $orderDataProvider = new ActiveDataProvider([
            'query' => \app\models\Order::find()->where(['user_id' => $id])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'orderDataProvider' => $orderDataProvider,
        ]);
    }

    /**
     * Создание нового пользователя
     * 
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');
            
            if ($user = $model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно создан.');
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновление пользователя
     * 
     * @param int $id ID пользователя
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException если пользователь не найден
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_UPDATE;
        
        // Заполнение данными из модели пользователя
        $model->setAttributes($user->getAttributes());
        
        if ($model->load(Yii::$app->request->post())) {
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');
            
            if ($model->update($user)) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен.');
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Удаление пользователя
     * 
     * @param int $id ID пользователя
     * @return \yii\web\Response
     * @throws NotFoundHttpException если пользователь не найден
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        
        // Проверяем, не пытаемся ли мы удалить текущего пользователя
        if ($user->id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Вы не можете удалить свой собственный аккаунт.');
            return $this->redirect(['index']);
        }
        
        // Логическое удаление (меняем статус на неактивный)
        $user->status = User::STATUS_INACTIVE;
        $user->save(false);
        
        Yii::$app->session->setFlash('success', 'Пользователь успешно удален.');
        return $this->redirect(['index']);
    }

    /**
     * Массовые действия с пользователями
     *
     * @return \yii\web\Response
     */
    public function actionBatchAction()
    {
        $action = Yii::$app->request->post('action');
        $selection = Yii::$app->request->post('selection', []);
        
        if (empty($selection)) {
            Yii::$app->session->setFlash('error', 'Выберите хотя бы одного пользователя.');
            return $this->redirect(['index']);
        }
        
        $currentUserId = Yii::$app->user->id;
        $count = 0;
        
        switch ($action) {
            case 'activate':
                foreach ($selection as $id) {
                    if ($id != $currentUserId) {
                        $user = User::findOne($id);
                        if ($user) {
                            $user->status = User::STATUS_ACTIVE;
                            if ($user->save(false)) {
                                $count++;
                            }
                        }
                    }
                }
                Yii::$app->session->setFlash('success', "Активировано пользователей: {$count}");
                break;
                
            case 'ban':
                foreach ($selection as $id) {
                    if ($id != $currentUserId) {
                        $user = User::findOne($id);
                        if ($user) {
                            $user->status = User::STATUS_BANNED;
                            if ($user->save(false)) {
                                $count++;
                            }
                        }
                    }
                }
                Yii::$app->session->setFlash('success', "Заблокировано пользователей: {$count}");
                break;
                
            case 'delete':
                foreach ($selection as $id) {
                    if ($id != $currentUserId) {
                        $user = User::findOne($id);
                        if ($user) {
                            $user->status = User::STATUS_INACTIVE;
                            if ($user->save(false)) {
                                $count++;
                            }
                        }
                    }
                }
                Yii::$app->session->setFlash('success', "Удалено пользователей: {$count}");
                break;
                
            default:
                Yii::$app->session->setFlash('error', 'Неизвестное действие.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Находит модель пользователя по ID
     * 
     * @param int $id ID пользователя
     * @return User модель пользователя
     * @throws NotFoundHttpException если пользователь не найден
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый пользователь не найден.');
    }
}