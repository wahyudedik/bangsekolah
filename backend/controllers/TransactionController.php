<?php

namespace backend\controllers;

use Yii;
use common\models\Transaction;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('transaction', 'index');

        $dataProvider = new ActiveDataProvider([
            'query' => Transaction::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
        ]);
    }

    /**
     * Displays a single Transaction model.
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

    // public function actionPrint($id)
    // {
    //     return $this->render('print', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    public function actionPrint($id = null)
    {
        $transaction = Transaction::findOne($id);
        $transactions = Transaction::find()->where(['student_id' => $transaction->student_id, 'created_at' => $transaction->created_at])->all();

        $content = $this->renderPartial('@app/views/print/payment-receipt', [
            'transaction' => $transaction,
            'transactions' => $transactions,
        ]);

        // return $content;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'receipt - '.$transaction->student->nisn.' - '.date('Ymd', strtotime($transaction->created_at)).'.pdf',
            // 'format' => [210, 330],
            'format' => [148, 210],
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            // 'marginTop' => '10',
            // 'marginLeft' => '15',
            // 'marginRight' => '15',
            'marginTop' => '5',
            'marginLeft' => '5',
            'marginRight' => '5',
            'content' => $content,
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // 'cssFile' => '@app/web/css/surat-penawaran.css',
            'cssInline' => '.border-top{border-top: 1px solid grey;}',
            'options' => [
                'showWatermarkText' => true,
                'showWatermarkImage' => true,
            ],
            'methods' => [
                'SetTitle' => 'Receipt - '.$transaction->student->nisn,
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($nisn = null, $name = null)
    {
        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Transaction model.
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
     * Deletes an existing Transaction model.
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
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
