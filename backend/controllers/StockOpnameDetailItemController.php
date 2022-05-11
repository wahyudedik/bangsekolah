<?php

namespace backend\controllers;

use Yii;
use common\models\StockOpnameDetail;
use common\models\StockOpnameDetailItem;
use common\models\StockOpnameDeletedItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockOpnameDetailItemController implements the CRUD actions for StockOpnameDetailItem model.
 */
class StockOpnameDetailItemController extends Controller
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
        ];
    }

    /**
     * Lists all StockOpnameDetailItem models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $stockOpnameDetail = StockOpnameDetail::findOne($id);
        $stockOpnameDetailItems = StockOpnameDetailItem::find()->where(['stock_opname_detail_id' => $id])->all();
        $stockOpnameDeletedItems = StockOpnameDeletedItem::find()->where(['stock_opname_detail_id' => $id])->all();

        return $this->render('index', [
            'stockOpnameDetail' => $stockOpnameDetail,
            'stockOpnameDetailItems' => $stockOpnameDetailItems,
            'stockOpnameDeletedItems' => $stockOpnameDeletedItems,
        ]);
    }

    /**
     * Displays a single StockOpnameDetailItem model.
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
     * Creates a new StockOpnameDetailItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockOpnameDetailItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StockOpnameDetailItem model.
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
     * Deletes an existing StockOpnameDetailItem model.
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
     * Finds the StockOpnameDetailItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockOpnameDetailItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockOpnameDetailItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
