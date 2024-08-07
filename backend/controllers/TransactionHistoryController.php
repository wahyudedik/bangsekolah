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

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * TransactionHistoryController implements the CRUD actions for Transaction model.
 */
class TransactionHistoryController extends Controller
{

    public function actionIndex($startdate = null, $enddate = null, $year = null, $class = null, $payment = null, $method = null)
    {
        \common\helpers\Sidebar::setActiveMenu('transaction-history', 'index');

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
            'year' => $year ? $year : '',
            'class' => $class ? $class : '',
            'payment' => $payment ? $payment : '',
            'method' => $method ? $method : '',
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

        $content = $this->renderPartial('@app/views/pdf-template/transaction-history', [
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
            'filename' => 'riwayat-transaksi-'.$startdate.'-'.$enddate.'-'.$class.'-'.$payment.'-'.$method.'.pdf',
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
                'SetTitle' => 'riwayat-transaksi-'.$startdate.'-'.$enddate.'-'.$class.'-'.$payment.'-'.$method.'.pdf',
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }

    public function actionExport($startdate, $enddate, $year, $class, $payment, $method)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

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
        // set main title
        $sheet->setCellValue('A1', 'DAFTAR PENERIMAAN PEMBAYARAN SISWA');
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A2', strtoupper($school->name));
        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A3', 'TAHUN '.$year);
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(TRUE);
        $sheet->getStyle('A2')->getFont()->setBold(TRUE);
        $sheet->getStyle('A3')->getFont()->setBold(TRUE);
        $sheet->setCellValue('A4', 'Periode : '.Strings::getFullDate($startdate).' s/d '. Strings::getFullDate($enddate));
        $sheet->mergeCells('A4:I4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // set title
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'TANGGAL');
        $sheet->setCellValue('C5', 'NIS');
        $sheet->setCellValue('D5', 'NAMA SISWA');
        $sheet->setCellValue('E5', 'NAMA PEMBAYARAN');
        $sheet->setCellValue('F5', 'KELAS');
        $sheet->setCellValue('G5', 'TUNAI');
        $sheet->setCellValue('H5', 'TRANSFER');
        $sheet->setCellValue('I5', 'JUMLAH');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getRowDimension('5')->setRowHeight(30);

        $column = 9;
        $row = 5;
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
        // $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
        //     ->getFill()
        //     ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
        //     ->getStartColor()
        //     ->setRGB('154360');
        // $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
        //     ->getFill()
        //     ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
        //     ->getStartColor()
        //     ->setRGB('2C3E50');
        // $arrStyle = [
        //     'font'  => [
        //         'bold'  => true,
        //         'color' => ['rgb' => 'FFFFFF'],
        //     ]
        // ];
        // $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)->applyFromArray($arrStyle);

        // style for body
        $style_row = array(
            'borders' => array(
                'top' => array('style'  => \PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => \PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => \PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => \PHPExcel_Style_Border::BORDER_THIN) 
            )
        );
        // populate records data
        $row = 5;
        $no = 0;
        $totalCash = 0;
        $totalTransfer = 0;
        foreach ($transaction as $trans) {
            $column = 9;
            $row++;
            $no++;
            // count cash and transfer
            if ($trans->payment_method == 'CASH') {
                $totalCash += $trans->amount;
            } else {
                $totalTransfer += $trans->amount;
            }
            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('G', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('H', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('I', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('A' . $row, ($no));
            $sheet->setCellValue('B' . $row, Strings::getFullDate($trans->date));
            $sheet->setCellValue('C' . $row, $trans->student->nis);
            $sheet->setCellValue('D' . $row, $trans->student->name);
            $sheet->setCellValue('E' . $row, $trans->invoice->description);
            $sheet->setCellValue('F' . $row, $trans->student->class);
            $sheet->setCellValue('G' . $row, $trans->payment_method == 'CASH' ? $trans->amount : '');
            $sheet->setCellValue('H' . $row, $trans->payment_method == 'TRANSFER' ? $trans->amount : '');
            $sheet->setCellValue('I' . $row, $trans->amount);
            // $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
            //     ->applyFromArray($style_row);

        }
        $row++;
        $sheet->getStyle('A', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A', $row)->getFont()->setBold(TRUE);
        $sheet->getStyle('G', $row)->getFont()->setBold(TRUE);
        $sheet->getStyle('H', $row)->getFont()->setBold(TRUE);
        $sheet->getStyle('I', $row)->getFont()->setBold(TRUE);
        $sheet->setCellValue('A'.$row, 'TOTAL');
        $sheet->setCellValue('G'.$row, $totalCash);
        $sheet->setCellValue('H'.$row, $totalTransfer);
        $sheet->setCellValue('I'.$row, $totalCash + $totalTransfer);
        $sheet->mergeCells('A'.$row.':'.'F'.$row);
        // $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
        //         ->applyFromArray($style_row);

        $filename = 'riwayat-transaksi-'.$startdate.'-'. $enddate . '.xlsx';

        ob_clean(); //Emptying the cache
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        // most important code (exit)
        exit();
    }
}
