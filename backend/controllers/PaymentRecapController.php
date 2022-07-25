<?php

namespace backend\controllers;

use Yii;

use common\models\SchoolClass;
use common\models\SchoolPayment;
use common\models\SchoolMonth;
use common\models\Year;
use common\models\Student;
use common\models\School;
use common\models\Transaction;
use common\helpers\Strings;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use kartik\mpdf\Pdf;

/**
 * PaymentRecapController implements the CRUD actions for Transaction model.
 */
class PaymentRecapController extends Controller
{

    public function actionIndex($startdate = null, $enddate = null, $year = null, $class = null, $payment = null, $method = null)
    {
        \common\helpers\Sidebar::setActiveMenu('payment-recap', 'index');

        $school_id = Yii::$app->session->get('school_id');
        $years = Year::find()->where(['status' => 'ACTIVE'])->all();
        $schoolPayments = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolMonths = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = Transaction::find()
            ->joinWith(['student', 'schoolPayment'])
            ->where(['student.school_id' => Yii::$app->session->get('school_id')]);

        if ($startdate) { $query->andFilterWhere(['and', ['>=', 'DATE(transaction.date)', $startdate]]); }
        if ($enddate) { $query->andFilterWhere(['and', ['<=', 'DATE(transaction.date)', $enddate]]); }
        if($year) { $query->andFilterWhere(['student.year' => $year]); }
        if($class) { $query->andFilterWhere(['student.class' => $class]); }
        if($payment) { $query->andFilterWhere(['or', ['like', 'school_payment.number', $payment]]); }
        if($method) { $query->andFilterWhere(['or', ['like', 'payment_method', $method]]); }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'year' => $year,
            'class' => $class,
            'payment' => $payment,
            'method' => $method,
            'years' => $years,
            'schoolPayments' => $schoolPayments,
            'schoolClasses' => $schoolClasses,
            'schoolMonths' => $schoolMonths,
            // 'students' => $students,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($startdate, $enddate, $year, $class, $payment, $method)
    {
        $school_id = Yii::$app->session->get('school_id');
        $school = School::findOne($school_id);

        $query = Transaction::find()
            ->joinWith(['student', 'schoolPayment'])
            ->where(['student.school_id' => $school_id]);

        if ($startdate) { $query->andFilterWhere(['and', ['>=', 'DATE(transaction.date)', $startdate]]); }
        if ($enddate) { $query->andFilterWhere(['and', ['<=', 'DATE(transaction.date)', $enddate]]); }
        if($year) { $query->andFilterWhere(['student.year' => $year]); }
        if($class) { $query->andFilterWhere(['student.class' => $class]); }
        if($payment) { $query->andFilterWhere(['or', ['like', 'school_payment.number', $payment]]); }
        if($method) { $query->andFilterWhere(['or', ['like', 'payment_method', $method]]); }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        $content = $this->renderPartial('@app/views/pdf-template/payment-recap', [
            'dataProvider' => $dataProvider,
            'school' => $school,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'class' => $class,
            'year' => $year,
        ]);

        // return $content;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'payment-recap-'.$startdate.'-'.$enddate.'-'.$class.'-'.$payment.'-'.$method.'.pdf',
            'format' => [210, 330],
            // 'format' => [148, 210],
            'orientation' => Pdf::ORIENT_LANDSCAPE,
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
                'SetTitle' => 'payment-recap-'.$startdate.'-'.$enddate.'-'.$class.'-'.$payment.'-'.$method.'.pdf',
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }

    public function actionExport($startdate, $enddate, $year, $class, $payment, $method)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // populate data
        $school_id = Yii::$app->session->get('school_id');
        $school = School::findOne($school_id);

        $query = Transaction::find()
            ->joinWith(['student', 'schoolPayment'])
            ->where(['student.school_id' => $school_id]);

        if ($startdate) { $query->andFilterWhere(['and', ['>=', 'DATE(transaction.date)', $startdate]]); }
        if ($enddate) { $query->andFilterWhere(['and', ['<=', 'DATE(transaction.date)', $enddate]]); }
        if($year) { $query->andFilterWhere(['student.year' => $year]); }
        if($class) { $query->andFilterWhere(['student.class' => $class]); }
        if($payment) { $query->andFilterWhere(['or', ['like', 'school_payment.number', $payment]]); }
        if($method) { $query->andFilterWhere(['or', ['like', 'payment_method', $method]]); }

        $transaction = $query->orderBy(['id' => SORT_DESC])->all();

        // set title
        $sheet->setCellValue('A1', 'NOMOR');
        $sheet->setCellValue('B1', 'TANGGAL');
        $sheet->setCellValue('C1', 'NIS');
        $sheet->setCellValue('D1', 'NAMA SISWA');
        $sheet->setCellValue('E1', 'NAMA PEMBAYARAN');
        $sheet->setCellValue('F1', 'KELAS');
        $sheet->setCellValue('G1', 'TUNAI');
        $sheet->setCellValue('H1', 'TRANSFER');
        $sheet->setCellValue('I1', 'JUMLAH');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getRowDimension('1')->setRowHeight(30);

        $column = 9;
        $row = 1;
        // foreach ($event->parentQuestions as $question) {
        //     $sheet->getColumnDimension(chr(65+($column)))->setWidth(30);
        //     $sheet->setCellValueByColumnAndRow($column++, $row, $question->title);

        //     // create only one header chile
        //     if (count($question->childs)>0) {
        //         $sheet->getColumnDimension(chr(65+($column)))->setWidth(50);
        //         $sheet->setCellValueByColumnAndRow($column++, $row, 'Sub Question ('.$question->title.')');
        //     }
        // }

        // styling
        $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('154360');
        $sheet->getStyleByColumnAndRow(0, $row, 9, $row)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('2C3E50');
        $arrStyle = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ]
        ];
        $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)->applyFromArray($arrStyle);

// populate records data
        $row = 1;
        foreach ($transaction as $trans) {
            $column = 9;
            $row++;
            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('G', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('H', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('I', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('A' . $row, ($row - 1));
            $sheet->setCellValue('B' . $row, Strings::getFullDate($trans->date));
            $sheet->setCellValue('C' . $row, $trans->student->nis);
            $sheet->setCellValue('D' . $row, $trans->student->name);
            $sheet->setCellValue('E' . $row, $trans->invoice->description);
            $sheet->setCellValue('F' . $row, $trans->student->class);
            $sheet->setCellValue('G' . $row, $trans->payment_method == 'CASH' ? $trans->amountRupiah : '');
            $sheet->setCellValue('H' . $row, $trans->payment_method == 'TRANSFER' ? $trans->amountRupiah : '');
            $sheet->setCellValue('I' . $row, $trans->amountRupiah);
            $sheet->getStyleByColumnAndRow(0, $row, 9, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('FFFFFF');

        }

        $filename = 'payment-recap-'.$startdate.'-'. $enddate . '.xlsx';
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        unset($objPHPExcel);
        // most important code (exit)
        exit;
    }

}
