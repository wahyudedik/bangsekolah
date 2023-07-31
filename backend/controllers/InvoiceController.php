<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

use common\models\School;
use common\models\Invoice;
use common\models\Student;
use common\models\SchoolMonth;
use common\models\Transaction;
use common\models\PaymentType;
use common\models\SchoolPayment;
use common\models\SchoolClass;

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
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex($search = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('invoice', 'index');

        if (!Yii::$app->session->get('school_id')) {
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $query = Student::find()->where(['school_id' => Yii::$app->session->get('school_id')]);
        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();

        // force fill default class
        /*
        if (!$class) {
            foreach ($classes as $cls) {
                $class = $cls->name;
                break;
            }
        }
        */

        if ($search !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'nis', $search],
                ['like', 'nisn', $search],
                ['like', 'name', $search],
            ]);
        }
        if ($class !== null) {
            if ($class !== 'ALL') {
                $query->andFilterWhere([
                    'and',
                    ['class' => $class],
                ]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('student', [
            'dataProvider' => $dataProvider,
            'search' => $search,
            'class' => $class,
            'classes' => $classes,
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
        $query = Invoice::find()->where(['student_id' => $student->id, 'year' => $student->year]);
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

            // get current class for redirection
            $currentClass = $model->class;

            // filter amount
            if ($model->amount <= 0) {
                Yii::$app->session->setFlash('danger', 'Jumlah tagihan tidak boleh kurang dari Rp. 0');
                return $this->redirect(['create', 'model' => $model]);
            }

            // search student
            $students = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'class' => $model->class, 'year' => $model->year, 'status' => 'ACTIVE'])->all();
            if (!$students) {
                Yii::$app->session->setFlash('danger', 'Tidak ada siswa di kelas '.$model->class.'. Tambahkan daftar siswa terlebih dahulu.');
                return $this->redirect(['create', 'model' => $model]);
            }

            // populate data
            $months = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
            $school = School::findOne(Yii::$app->session->get('school_id'));

            // total data
            $totalInvoice = 0;

            foreach ($students as $student) {
                if ($model->schoolPayment->paymentType->name == 'MONTHLY') {
                    $monthNumber = 1;
                    foreach ($months as $month) {

                        // check if invoice has been created
                        $exist = Invoice::find()->where([
                            'year' => $model->year,
                            'student_id' => $student->id,
                            'school_payment_id' => $model->school_payment_id,
                            'month' => $month->name,
                        ])->one();

                        if (!$exist) {

                            // get tahun ajaran
                            $year = '';
                            if($monthNumber <= 6) {
                                $year = substr($model->year, 0, 4);
                            } else {
                                $year = substr($model->year, -4);
                            }

                            // generate invoice number
                            $lastNumber = $school->invoice_number;
                            $invoiceNumber = str_pad($lastNumber+1, 5, '0', STR_PAD_LEFT).'/INV/'.$school->number.'/'.$month->month.'/'.$year;
                            $lastNumber++;
                            $school->invoice_number = $lastNumber;
                            $school->save();

                            $invoice = new Invoice();
                            $invoice->school_id = $school->id;
                            $invoice->student_id = $student->id;
                            $invoice->school_payment_id = $model->school_payment_id;
                            $invoice->number = $invoiceNumber;
                            $invoice->year = $model->year;
                            $invoice->month = $month->name;
                            $invoice->description = $model->schoolPayment->name;
                            $invoice->amount = $model->amount;
                            $invoice->paid_amount = 0;
                            $invoice->date = date('Y-m-01', strtotime(''.$year.'-'.$month->month));
                            $invoice->due_date = date('Y-m-t', strtotime(date('Y-m-01', strtotime(''.$year.'-'.$month->month))));
                            $invoice->status = 'UNPAID';
                            $invoice->user_id = Yii::$app->user->identity->id;
                            
                            if (!$invoice->save()) {
                                foreach ($invoice->getErrors() as $error) {
                                    $message = $error[0];
                                }
                                Yii::$app->session->setFlash('danger', $message);
                                return $this->redirect(['create', 'model' => $model]);
                            }
                            $totalInvoice++;
                            $monthNumber++;
                        }
                    }

                } else {

                    // generate invoice number
                    $lastNumber = $school->invoice_number;
                    $invoiceNumber = str_pad($lastNumber+1, 5, '0', STR_PAD_LEFT).'/INV/'.$school->number.'/'.date('m/Y', strtotime($model->date));
                    $lastNumber++;
                    $school->invoice_number = $lastNumber;
                    $school->save();

                    $invoice = new Invoice();
                    $invoice->school_id = $school->id;
                    $invoice->student_id = $student->id;
                    $invoice->school_payment_id = $model->school_payment_id;
                    $invoice->number = $invoiceNumber;
                    $invoice->year = $model->year;
                    $invoice->description = $model->schoolPayment->name;
                    $invoice->amount = $model->amount;
                    $invoice->paid_amount = 0;
                    $invoice->date = $model->date;
                    $invoice->due_date = $model->due_date;
                    $invoice->status = 'UNPAID';
                    $invoice->user_id = Yii::$app->user->identity->id; 

                    if (!$invoice->save()) {
                        foreach ($invoice->getErrors() as $error) {
                            $message = $error[0];
                        }
                        Yii::$app->session->setFlash('danger', $message);
                        return $this->redirect(['create', 'model' => $model]);
                    }   
                    $totalInvoice++;
                }
            }

            if ($totalInvoice > 0) {
                Yii::$app->session->setFlash('success', 'Total '.$totalInvoice.' tagihan berhasil dibuat untuk '.count($students).' siswa');
            } else {
                Yii::$app->session->setFlash('danger', 'Tidak ada tagihan yang dibuat. Periksa kembali apakah tagihan untuk pembayaran tersebut sudah pernah dibuat.');
            }
            return $this->redirect(['index', 'class' => $currentClass]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateSingle($student)
    {
        $student = Student::findOne($student);
        if (!$student) {
            return $this->redirect(['index', 'student' => $student->id]);
        }

        $model = new Invoice();
        $model->student_id = $student->id;
        $model->school_id = $student->school_id;
        $model->year = $student->year;

        $school = $student->school;
        $totalInvoice = 0;

        if ($model->load(Yii::$app->request->post()) ) {

            // filter amount
            if ($model->amount <= 0) {
                Yii::$app->session->setFlash('danger', 'Jumlah tagihan tidak boleh kurang dari Rp. 0');
                return $this->redirect(['create-single', 'student' => $student->id]);
            }

            if ($model->schoolPayment->paymentType->name == 'MONTHLY') {
                $monthNumber = 1;
                foreach ($months as $month) {

                    // check if invoice has been created
                    $exist = Invoice::find()->where([
                        'year' => $model->year,
                        'student_id' => $student->id,
                        'school_payment_id' => $model->school_payment_id,
                        'month' => $month->name,
                    ])->one();

                    if (!$exist) {

                        // get tahun ajaran
                        $year = '';
                        if($monthNumber <= 6) {
                            $year = substr($model->year, 0, 4);
                        } else {
                            $year = substr($model->year, -4);
                        }

                        // generate invoice number
                        $lastNumber = $school->invoice_number;
                        $invoiceNumber = str_pad($lastNumber+1, 5, '0', STR_PAD_LEFT).'/INV/'.$school->number.'/'.$month->month.'/'.$year;
                        $lastNumber++;
                        $school->invoice_number = $lastNumber;
                        $school->save();

                        $invoice = new Invoice();
                        $invoice->school_id = $school->id;
                        $invoice->student_id = $student->id;
                        $invoice->school_payment_id = $model->school_payment_id;
                        $invoice->number = $invoiceNumber;
                        $invoice->year = $model->year;
                        $invoice->month = $month->name;
                        $invoice->description = $model->schoolPayment->name;
                        $invoice->amount = $model->amount;
                        $invoice->paid_amount = 0;
                        $invoice->date = date('Y-m-01', strtotime(''.$year.'-'.$month->month));
                        $invoice->due_date = date('Y-m-t', strtotime(date('Y-m-01', strtotime(''.$year.'-'.$month->month))));
                        $invoice->status = 'UNPAID';
                        $invoice->user_id = Yii::$app->user->identity->id;
                        
                        if (!$invoice->save()) {
                            foreach ($invoice->getErrors() as $error) {
                                $message = $error[0];
                            }
                            Yii::$app->session->setFlash('danger', $message);
                            return $this->redirect(['create', 'model' => $model]);
                        }
                        $totalInvoice++;
                        $monthNumber++;
                    }
                }

            } else {

                // generate invoice number
                $lastNumber = $school->invoice_number;
                $invoiceNumber = str_pad($lastNumber+1, 5, '0', STR_PAD_LEFT).'/INV/'.$school->number.'/'.date('m/Y', strtotime($model->date));
                $lastNumber++;
                $school->invoice_number = $lastNumber;
                $school->save();

                $invoice = new Invoice();
                $invoice->school_id = $school->id;
                $invoice->student_id = $student->id;
                $invoice->school_payment_id = $model->school_payment_id;
                $invoice->number = $invoiceNumber;
                $invoice->year = $model->year;
                $invoice->description = $model->schoolPayment->name;
                $invoice->amount = $model->amount;
                $invoice->paid_amount = 0;
                $invoice->date = $model->date;
                $invoice->due_date = $model->due_date;
                $invoice->status = 'UNPAID';
                $invoice->user_id = Yii::$app->user->identity->id; 

                if (!$invoice->save()) {
                    foreach ($invoice->getErrors() as $error) {
                        $message = $error[0];
                    }
                    Yii::$app->session->setFlash('danger', $message);
                    return $this->redirect(['create-single', 'student' => $student->id]);
                }   
                $totalInvoice++;
            }

            if ($totalInvoice > 0) {
                Yii::$app->session->setFlash('success', 'Total '.$totalInvoice.' tagihan berhasil dibuat untuk '.$student->name);
            } else {
                Yii::$app->session->setFlash('danger', 'Tidak ada tagihan yang dibuat. Periksa kembali apakah tagihan untuk pembayaran tersebut sudah pernah dibuat.');
            }
            return $this->redirect(['invoices', 'student' => $student->id]);
        }

        return $this->render('create-single', [
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

    public function actionChangeAmount($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('change-amount', [
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

    public function actionGetPaymentType($payment)
    {
        $schoolPayment = SchoolPayment::findOne($payment);
        if ($schoolPayment) {
            return $schoolPayment->paymentType->label;
        } else {
            return '';
        }
    }

    public function actionGetStudentClass($class, $year)
    {
        $students = Student::find()->where([
            'school_id' => Yii::$app->session->get('school_id'),
            'year' => $year,
            'class' => $class,
            'status' => 'ACTIVE',
        ])->all();
        
        return count($students);
    }

}
