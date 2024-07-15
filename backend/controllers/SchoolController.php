<?php

namespace backend\controllers;

use Yii;
use common\models\School;
use common\models\UserSchool;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SchoolController implements the CRUD actions for School model.
 */
class SchoolController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                // 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        ];
    }

    /**
     * Lists all School models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('school', 'index');

        $query = School::find()->where(['status' => 'ACTIVE']);

        if ($q !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'name', $q],
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
        ]);
    }

    /**
     * Displays a single School model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $user_school = UserSchool::find()->where(['school_id' => $id])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'user_school' => $user_school,
        ]);
    }

    /**
     * Creates a new School model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new School();
        $model->invoice_number = 0;
        $model->transaction_number = 0;
        $model->status = 'ACTIVE';

        if ($model->load(Yii::$app->request->post())) {
            $model->number = strtoupper($model->number);

            // Check if captcha is empty
            $captchaEmpty = empty($model->captcha);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // If captcha is empty, remove captcha related errors
                if ($captchaEmpty) {
                    unset($model->errors['captcha']);
                }

                foreach ($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error[0]);
                }
                return $this->redirect(['create']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing School model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing School model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the School model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return School the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = School::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
