<?php

namespace backend\controllers;

use Yii;
use common\models\UserSchool;
use common\models\User;
use common\models\School;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserSchoolController implements the CRUD actions for UserSchool model.
 */
class UserSchoolController extends Controller
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
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserSchool models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('user-school', 'index');

        $dataProvider = new ActiveDataProvider([
            'query' => UserSchool::find()->where(['school_id' => Yii::$app->session->get('school_id')]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
        ]);
    }

    /**
     * Displays a single UserSchool model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChangePassword($id)
    {
        $model = User::findOne($id);
        $user_school = UserSchool::find()->where(['user_id' => $id])->one();

        $model->mode_change_password = true;
        if ($model->load(Yii::$app->request->post())) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Password changed successfully');
            } else {
                Yii::$app->session->setFlash('danger', 'Password failed to change');
            }
            return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->render('change-password', [
                'model' => $model,
                'user_school' => $user_school,
            ]);
        }
    }

    /**
     * Creates a new UserSchool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            // cek jika username sudah terdaftar maka ditolak
            $user = User::find()->where(['username' => $model->username])->one();
            if ($user) {
                Yii::$app->session->setFlash('danger', 'Username already registered');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);

            if ($model->save()) {
                $user_school = new UserSchool;
                $user_school->user_id = $model->id;
                $user_school->school_id = Yii::$app->session->get('school_id');
                $user_school->save();

                Yii::$app->session->setFlash('success', 'User saved successfully');
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserSchool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user_school = UserSchool::findOne($id);
        $model = User::findOne($user_school->user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $user_school->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'user_school' => $user_school,
        ]);
    }

    /**
     * Deletes an existing UserSchool model.
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
     * Finds the UserSchool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserSchool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSchool::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
