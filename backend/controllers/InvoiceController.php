<?php

namespace backend\controllers;

use Yii;
use common\models\Invoice;
use common\models\SchoolPayment;
use common\models\PaymentType;
use common\models\Student;
use common\models\SchoolMonth;
use common\models\Transaction;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('invoice', 'index');

        $query = Student::find()
            ->where(['school_id' => Yii::$app->session->get('school_id')]);

        // if ($q !== null) {
        //     $query->andFilterWhere([
        //         'or',
        //         ['like', 'description', $q],
        //     ]);
        // }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('student', [
            'dataProvider' => $dataProvider,
            // 'students' => $students,
            'q' => $q,
        ]);
    }

    public function actionView($id)
    {
        $query = Transaction::find()->where(['invoice_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionInvoices($student)
    {
        $student = Student::findOne($student);
        $query = Invoice::find()->where(['student_id' => $student->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('invoices', [
            'student' => $student,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();

        if ($model->load(Yii::$app->request->post()) ) {
            //filter amount
            if ($model->amount < 0) {
                Yii::$app->session->setFlash('danger', 'Jumlah tagihan tidak boleh kurang dari Rp. 0');
                return $this->redirect(['create', 'model' => $model]);
            }

            //search student
            $students = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'class' => $model->kelas])->all();
            if (!$students) {
                Yii::$app->session->setFlash('danger', 'Silahkan tambahkan daftar siswa');
                return $this->redirect(['create', 'model' => $model]);
            }
            
            foreach ($students as $student) {
                if ($model->jenis == "MONTHLY") {
                    $school_months = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
                    $x = 1;
                    foreach ($school_months as $school_month) {
                        $year = '';
                        if($x <= 6){
                            $year = substr($model->year,0,4);
                        } else {
                            $year = substr($model->year,-4);
                        }
                        $invoice = new Invoice();
                        $invoice->school_id = Yii::$app->session->get('school_id');
                        $invoice->student_id = $student->id;
                        $invoice->school_payment_id = $model->school_payment_id;
                        $invoice->number = ''.$x;
                        $invoice->year = $model->year;
                        $invoice->month = $school_month->name;
                        $invoice->description = $model->description;
                        $invoice->amount = $model->amount;
                        $invoice->paid_amount = 0;
                        $invoice->date = date('Y-m-01', strtotime(''.$year.'-'.$school_month->month));
                        $invoice->due_date = date('Y-m-t', strtotime($invoice->date));
                        $invoice->status = "UNPAID";
                        $invoice->user_id = Yii::$app->user->identity->id;
                        
                        if (!$invoice->save()) {
                            foreach ($invoice->getErrors() as $error) {
                                $message = $error[0];
                            }
                            Yii::$app->session->setFlash('danger', $message);
                            return $this->redirect(['create', 'model' => $model]);
                        }
                        $x++;

                    }

                    

                } else {
                    $invoice = new Invoice();
                    $invoice->school_id = Yii::$app->session->get('school_id');
                    $invoice->student_id = $student->id;
                    $invoice->school_payment_id = $model->school_payment_id;
                    $invoice->year = $model->year;
                    $invoice->description = $model->description;
                    $invoice->amount = $model->amount;
                    $invoice->paid_amount = 0;
                    $invoice->date = $model->date;
                    $invoice->due_date = $model->due_date;
                    $invoice->status = "UNPAID";
                    $invoice->user_id = Yii::$app->user->identity->id; 

                    if (!$invoice->save()) {
                        foreach ($invoice->getErrors() as $error) {
                            $message = $error[0];
                        }
                        Yii::$app->session->setFlash('danger', $message);
                        return $this->redirect(['create', 'model' => $model]);
                    }   
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Invoice model.
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
     * Deletes an existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList($param)
    {
        $school_payment = SchoolPayment::findOne($param);
        echo $school_payment->paymentType->name;
        //echo "<option value='" .$school_payment->paymentType->id. "'>".$school_payment->paymentType->name."</option>";
    }

}
