<?php

namespace yuncms\group\frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yuncms\group\models\GroupMember;
use yuncms\group\models\Group;
use yii\web\NotFoundHttpException;
use yuncms\group\frontend\models\GroupSearch;
use yuncms\payment\models\Payment;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','newest', 'view', 'tag', 'online-stream', 'online-user'],
                        'roles' => ['@', '?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['play', 'pay', 'involved','started'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Group models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Group model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Group model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Group model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Pay a single Stream model.
     * @param int $id
     * @param string $gateway
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionPay($id, $gateway = null)
    {
        /** @var  Group $model */
        $model = $this->findModel($id);

        if ($model->price != 0 && !array_key_exists($gateway, Yii::$app->getModule('payment')->gateways)) {
            Yii::$app->session->setFlash('warning', Yii::t('group', 'Illegal parameters.'));
            return $this->redirect(['/group/group/view', 'uuid' => $model->id]);
        }
        $join = GroupMember::findOne($model,Yii::$app->user->id);

        if ($join->status == GroupMember::STATUS_PENDING) {
            $payment = new Payment([
                'currency' => 'CNY',
                'money' => $model->price,
                'trade_type' => Payment::TYPE_NATIVE,
                'model_id' => $join->id,
                'model' => get_class($join),
                'return_url' => Url::to(['/live/stream/view', 'uuid' => $model->uuid], true)
            ]);
            $payment->gateway = $gateway;
            if ($payment->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['url' => Url::to(['/payment/default/pay', 'id' => $payment->id])];
                }
                return $this->redirect(['/payment/default/pay', 'id' => $payment->id]);
            }
        } else {
            if ($model->price == 0) {
                $model->updateCounters(['applicants' => 1]);//更新报名人数
                Yii::$app->session->setFlash('success', Yii::t('group', 'Sign up success.'));
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('group', 'Please do not join again.'));
            }
        }
        return $this->redirect(['/group/group/view', 'uuid' => $model->id]);
    }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
