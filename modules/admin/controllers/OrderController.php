<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\Order;
use app\models\OrderItem;
use app\models\search\OrderSearch;
use app\models\OrderLog;
use yii\web\Response;

class OrderController extends Controller
{
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
        $model = Order::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Заказ не найден');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatusAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
    
        $order = Order::findOne($id);
        if ($order) {
            $order->status = $status;
            if ($order->save(false)) {
                OrderLog::add($order->id, 'Изменён статус', "Новый статус: $status");
                return ['success' => true];
            }
        }
        return ['success' => false];
    }

    public function actionComment($id)
    {
        $model = Order::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            OrderLog::add($model->id, 'Комментарий администратора', $model->admin_comment);
            Yii::$app->session->setFlash('success', 'Комментарий сохранён');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

}
