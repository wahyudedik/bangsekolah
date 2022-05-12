<?php

namespace backend\controllers;

use Yii;
use common\models\SchoolMonth;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BulanController implements the CRUD actions for SchoolMonth model.
 */
class BulanController extends Controller
{

    /**
     * Lists all SchoolMonth models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('bulan', 'index');

        $query = SchoolMonth::find()
            ->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE']);

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
     * Displays a single SchoolMonth model.
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
     * Creates a new SchoolMonth model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SchoolMonth();

        if ($model->load(Yii::$app->request->post())) {
            $model->school_id = Yii::$app->session->get('school_id');
            $model->status = 'ACTIVE';

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['create', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SchoolMonth model.
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
     * Deletes an existing SchoolMonth model.
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

        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SchoolMonth model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SchoolMonth the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SchoolMonth::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
