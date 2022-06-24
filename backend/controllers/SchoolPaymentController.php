<?php

namespace backend\controllers;

use Yii;
use common\models\SchoolPayment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SchoolPaymentController implements the CRUD actions for SchoolPayment model.
 */
class SchoolPaymentController extends Controller
{

    /**
     * Lists all SchoolPayment models.
     * @return mixed
     */
    public function actionIndex($search = null)
    {
        \common\helpers\Sidebar::setActiveMenu('school-payment', 'index');

        $query = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE']);
        if ($search) {
            $query->andFilterWhere([
                'or',
                ['like', 'name', $search]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'search' => $search,
        ]);
    }

    /**
     * Displays a single SchoolPayment model.
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

    /**
     * Creates a new SchoolPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SchoolPayment();
        $model->school_id = Yii::$app->session->get('school_id');
        $model->status = 'ACTIVE';

        if ($model->load(Yii::$app->request->post())) {

            // validasi data form
            $number = strtoupper(str_replace(' ', '', $model->number));
            $model->number = $number;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Master pembayaran berhasil disimpan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->errors as $error) {
                    Yii::$app->session->setFlash('danger', $error[0]);
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SchoolPayment model.
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
     * Deletes an existing SchoolPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 'NONACTIVE';
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SchoolPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SchoolPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SchoolPayment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
