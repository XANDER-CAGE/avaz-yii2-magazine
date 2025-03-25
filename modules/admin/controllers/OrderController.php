<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Order;
use app\models\OrderItem;
use app\models\search\OrderSearch;
use app\models\OrderLog;
use yii\web\Response;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'change-status-ajax' => ['POST'],
                    'comment' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatusAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
    
        $order = $this->findModel($id);
        if ($order) {
            $oldStatus = $order->status;
            $order->status = $status;
            if ($order->save(false)) {
                OrderLog::add($order->id, 'Изменён статус', "Статус изменен с '{$oldStatus}' на '{$status}'");
                return ['success' => true];
            }
        }
        return ['success' => false];
    }

    public function actionChangeStatus($id, $status)
    {
        $model = $this->findModel($id);
        $oldStatus = $model->status;
        $model->status = $status;
        
        if ($model->save(false)) {
            OrderLog::add($model->id, 'Изменён статус', "Статус изменен с '{$oldStatus}' на '{$status}'");
            Yii::$app->session->setFlash('success', 'Статус заказа успешно изменен');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при изменении статуса заказа');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionComment($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            OrderLog::add($model->id, 'Комментарий администратора', $model->admin_comment);
            Yii::$app->session->setFlash('success', 'Комментарий сохранён');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * Находит модель заказа по ID
     * @param integer $id
     * @return Order модель заказа
     * @throws NotFoundHttpException если заказ не найден
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый заказ не найден.');
    }
}