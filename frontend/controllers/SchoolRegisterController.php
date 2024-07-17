<?php

namespace frontend\controllers;

use Yii;
use common\models\School;
use common\models\User;
use common\models\UserSchool;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SchoolController implements the CRUD actions for School model.
 */
class SchoolRegisterController extends Controller
{
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha', 'create'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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
     * Lists all School models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('school-register', 'index');

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
        $school = new School();
        $admin = new User();

        if ($school->load(Yii::$app->request->post()) && $admin->load(Yii::$app->request->post())) {

            $school->expired_date = date('Y-m-d', strtotime('+1 month'));
            $school->invoice_number = 0;
            $school->transaction_number = 0;
            $school->status = 'ACTIVE';
            if ($school->save()) {
                $admin->role_id = 2;
                $admin->username = strtolower(str_replace(' ', '', $admin->name));
                $admin->setPassword($admin->password);
                $admin->generateAuthKey();
                if ($admin->save()) {
                    $user = new UserSchool();

                    $user->school_id = $school->id;
                    $user->user_id = $admin->id;
                    $user->save();
                    return $this->redirect('https://banksekolah.com/backend/web');
                } else {
                    foreach ($admin->getErrors() as $error) {
                        $message = $error[0];
                        Yii::$app->session->setFlash('danger', $message);
                    }
                }
            } else {
                foreach ($school->getErrors() as $error) {
                    $message = $error[0];
                    Yii::$app->session->setFlash('danger', $message);
                }
            }
        }

        return $this->render('form', [
            'school' => $school,
            'admin' => $admin,
            // 'school' => $school,
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
