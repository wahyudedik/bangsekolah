<?php

namespace backend\controllers;

use Yii;

use common\models\Transaction;
use common\models\Student;
use common\models\Invoice;
use common\models\School;
use common\models\SchoolPayment;
use common\models\SchoolClass;
use common\models\Year;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex($class = null, $nisn = null, $startdate = null, $enddate = null, $name = null, $payment = null)
    {
        \common\helpers\Sidebar::setActiveMenu('transaction', 'index');

        if (!Yii::$app->session->get('school_id')) {
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $query = Transaction::find()
            ->joinWith(['student', 'schoolPayment'])
            ->where(['student.school_id' => Yii::$app->session->get('school_id')]);

        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        if ($startdate) { $query->andFilterWhere(['and', ['>=', 'DATE(transaction.date)', $startdate]]); }
        if ($enddate) { $query->andFilterWhere(['and', ['<=', 'DATE(transaction.date)', $enddate]]); }
        if($class) { $query->andFilterWhere(['student.class' => $class]); }
        if($nisn) { $query->andFilterWhere(['student.nisn' => $nisn]); }
        if($name) { $query->andFilterWhere(['or', ['like', 'student.name', $name]]); }
        if($payment) { $query->andFilterWhere(['or', ['like', 'school_payment.name', $payment]]); }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        $schoolPayments = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'nisn' => $nisn,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'name' => $name,
            'payment' => $payment,
            'class' => $class,
            'schoolPayments' => $schoolPayments,
            'schoolClasses' => $schoolClasses,
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
        $model = $this->findModel($id);

        // get other transaction with same number
        $query = Transaction::find()->where(['number' => $model->number]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPrint($id = null)
    {
        $transaction = Transaction::findOne($id);
        $transactions = Transaction::find()->where(['number' => $transaction->number])->all();

        $content = $this->renderPartial('@app/views/pdf-template/payment-receipt', [
            'transaction' => $transaction,
            'transactions' => $transactions,
        ]);

        // return $content;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'receipt - '.$transaction->student->nisn.' - '.date('Ymd', strtotime($transaction->created_at)).'.pdf',
            'format' => [215, 330],
            // 'format' => [330, 215],
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
            'cssInline' => '
            
            .border-top{border-top: 1px solid grey;}
            .monospace{font-family: monospace;}
            .small{font-size:9px;}
            .medium{font-size:10px;}

            ',
            
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
    public function actionCreate($nis = null, $name = null)
    {
        $student = null;
        $invoices = null;

        if ($nis) {
            $student = Student::find()->where(['nis' => $nis])->one();
        } else if ($name) {
            $student = Student::find()->where(['like', 'name', $name, false])->one();
        }
        if ($student) {
            $invoices = Invoice::find()->where(['student_id' => $student->id, 'school_id' => $student->school_id, 'year' => $student->year])->all();
        }

        if (isset($_POST['submit'])) {
            $invoices = $_POST['invoice'];
            $amounts = $_POST['amount'];
            $proofs = $_POST['proof'];

            // generate transaction number
            $school = School::findOne(Yii::$app->session->get('school_id'));
            $lastNumber = $school->transaction_number;
            $transactionNumber = str_pad($lastNumber+1, 5, '0', STR_PAD_LEFT).'/TRX/'.$school->number.'/'.date('m/Y');
            $lastNumber++;
            $school->transaction_number = $lastNumber;
            $school->save();

            $i = 0;
            $saved = 0;
            foreach ($invoices as $invoice_id) {
                $amount = $amounts[$i];
                $proof = $proofs[$i];

                $invoice = Invoice::findOne($invoice_id);
                if ($invoice) {

                    $transaction = new Transaction();
                    $transaction->school_id = Yii::$app->session->get('school_id');
                    $transaction->invoice_id = $invoice->id;
                    $transaction->student_id = $invoice->student_id;
                    $transaction->school_payment_id = $invoice->school_payment_id;
                    $transaction->number = $transactionNumber;
                    $transaction->amount = $amount;
                    $transaction->date = date('Y-m-d');
                    $transaction->note = '';
                    $transaction->payment_method = $proof;
                    $transaction->user_id = Yii::$app->user->identity->id;
                    $transaction->save();

                    // update invoice paid_amount & status
                    $invoice->paid_amount = $invoice->paid_amount + $amount;
                    $invoice->save();
                    if ($invoice->paid_amount == $invoice->amount) {
                         $invoice->status = 'PAID';
                         $invoice->save();
                    }

                    $saved++;
                }
                $i++;
            }

            if ($saved > 0) {
                Yii::$app->session->setFlash('success', $saved.' transaksi berhasil disimpan. Klik detail transaksi untuk mencetak bukti bayar');
            } else {
                Yii::$app->session->setFlash('danger', 'Tidak ada transaksi yang tersimpan');
            }
            return $this->redirect(['transaction/index']);
        }

        return $this->render('create', [
            'nis' => $nis,
            'name' => $name,
            'student' => $student,
            'invoices' => $invoices,
        ]);
    }

    public function actionImport()
    {
        $model = new Transaction();
        $model->school_id = Yii::$app->session->get('school_id');

        if ($model->load(Yii::$app->request->post())) {

            $import = UploadedFile::getInstance($model, 'file');
            if(isset($import)){
                $exploded_name = explode('.', $import->name);
                $extension = end($exploded_name);
                $model->file = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/import/') . $model->file;
                $import->saveAs($path);

                $totalItem = 0;
                $transaction = Yii::$app->db->beginTransaction();

                $result = $this->populateDataExcel($path, 0);
                if ($result['status'] == 'ERROR') {
                    Yii::$app->session->setFlash('danger', $result['data']);
                } else {
                    $data = $result['data'];

                    foreach ($data as $item) {
                        $model = new Transaction();
                        $model->school_id = Yii::$app->session->get('school_id');
                        $model->invoice_id = $item['invoice'];
                        $model->student_id = $item['student'];
                        $model->school_payment_id = $item['payment'];
                        $model->number = $item['number'];
                        $model->amount = $item['amount'];
                        $model->date = date('Y-m-d', strtotime($item['date']));
                        $model->note = '';
                        $model->payment_method = 'CASH';
                        $model->user_id = Yii::$app->user->identity->id;

                        if ($model->save()) {
                            $invoice = Invoice::findOne($item['invoice']);
                            // check paid and unpaid
                            $total = $invoice->amount - $item['amount'];
                            if ($total <= 0) {
                                $invoice->status = 'PAID';
                            }
                            $invoice->paid_amount = $item['amount'];
                            $invoice->save();
                        } else {
                            foreach ($model->errors as $error) {
                                Yii::$app->session->setFlash('danger', $error[0]);
                            }
                            return $this->render('create', [
                                'id' => $model->id
                            ]);
                        }
                        $totalItem++;
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', $totalItem.' transaksi berhasil diimport');
                return $this->redirect(['index']);
            }
        }

        return $this->render('import', [
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

    private function populateDataExcel($fileName, $sheet = 0)
    {

        // populate array for data validation
        $arrYear = [];
        $years = Year::find()->all();
        foreach ($years as $year) {
            $arrYear[] = $year->name;
        }

        $arrMonth = [];
        $months = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();
        foreach ($months as $month) {
            $arrYear[] = $month->name;
        }

        // PHP Excel init        
        $phpexcel = \PHPExcel_IOFactory::load($fileName);
        $worksheet = $phpexcel->getSheet($sheet);
        $dataExcel = $worksheet->toArray();
        $error = null;
        $itemArray = [];
        $row_number = 0;
        if ($sheet == 0) {
            // generate transaction number
            $school = School::findOne(Yii::$app->session->get('school_id'));
            $lastNumber = $school->transaction_number;
            $transactionNumber = str_pad($lastNumber+1, 5, '0', STR_PAD_LEFT).'/TRX/'.$school->number.'/'.date('m/Y');
            $lastNumber++;
            $school->transaction_number = $lastNumber;
            $school->save();

            foreach ($dataExcel as $rowExcelData) {
                if ($row_number++ > 0 && $rowExcelData[0] !== null) {
                    // check nisn student
                    $student = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'),'nisn' => trim((string)$rowExcelData[1])])->one();
                    if (!$student) {
                        $error = 'NISN '.$student->nisn.' belum terdaftar ';
                        break;
                    }

                    // check school payment
                    $schoolPayment = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'number' => trim((string)$rowExcelData[2])])->one();
                    if (!$schoolPayment) {
                        $error = 'Kode pembayaran tidak sesuai';
                        break;
                    }

                    // check invoice
                    $invoice = Invoice::find()->where([
                        'school_id' => Yii::$app->session->get('school_id'), 
                        'student_id' => $student->id, 
                        'month' => trim((string)$rowExcelData[3]) == '' ? NULL : trim((string)$rowExcelData[3]), 
                        'year' => trim((string)$rowExcelData[4]), 
                        'description' => $schoolPayment->name])
                        ->one();

                    if (!$invoice) {
                        $error = 'Tagihan belum terdaftar';
                        break;
                    }

                    // validasi data
                    $year = trim((string)$rowExcelData[4]);
                    $month = trim((string)$rowExcelData[3]);
                    if (in_array($year, $arrYear) && in_array($month, $arrMonth)) {
                        $itemArray[] = [
                            'invoice' => $invoice->id,
                            'student' => $student->id,
                            'payment' => $schoolPayment->id,
                            'number' => $transactionNumber,
                            'amount' => trim((string)$rowExcelData[5]),
                            'date' => trim((string)$rowExcelData[0]),
                            'month' => trim((string)$rowExcelData[3]),
                            'year' => trim((string)$rowExcelData[4]),
                        ];
                    } else {
                        $error = 'Data tidak valid pada baris ke '.$row_number;
                        break;
                    }
                }
            }
        }
        @unlink($fileName);
        if ($error != NULL) {
            return [
                'status' => 'ERROR',
                'data' => $error
            ];
        } else {
            return [
                'status' => 'OK',
                'data' => $itemArray
            ];
        }
    }
}
