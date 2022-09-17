<?php

namespace backend\controllers;

use Yii;

use common\models\SchoolClass;
use common\models\SchoolPayment;
use common\models\SchoolMonth;
use common\models\Year;
use common\models\Student;
use common\models\School;
use common\models\Invoice;

use yii\web\Controller;
use kartik\mpdf\Pdf;

/**
 * PaymentRecapController implements the CRUD actions for Transaction model.
 */
class PaymentRecapController extends Controller
{

    public function actionIndex($year = null, $class = null, $payment = null)
    {
        \common\helpers\Sidebar::setActiveMenu('payment-recap', 'index');

        $years = Year::find()->where(['status' => 'ACTIVE'])->all();
        $schoolPayments = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolMonths = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        $students = null;
        if ($year && $class && $payment) {
            $payment = SchoolPayment::find()->where(['number' => $payment])->one();
            $students = Student::find()->where(['year' => $year, 'class' => $class, 'school_id' => Yii::$app->session->get('school_id')])->all();
        }

        return $this->render('index', [
            'class' => $class,
            'year' => $year,
            'payment' => $payment,
            'years' => $years,
            'schoolPayments' => $schoolPayments,
            'schoolClasses' => $schoolClasses,
            'schoolMonths' => $schoolMonths,
            'students' => $students,
        ]);
    }

    public function actionDownload($year, $class, $payment)
    {
        $schoolMonths = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $students = null;
        if ($year && $class && $payment) {
            $school = School::findOne(Yii::$app->session->get('school_id'));
            $payment = SchoolPayment::find()->where(['number' => $payment])->one();
            $students = Student::find()->where(['year' => $year, 'class' => $class, 'school_id' => Yii::$app->session->get('school_id')])->all();
        }

        $content = $this->renderPartial('@app/views/pdf-template/payment-recap', [
            'year' => $year,
            'class' => $class,
            'school' => $school,
            'payment' => $payment,
            'students' => $students,
            'schoolMonths' => $schoolMonths,
        ]);

        // return $content;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'payment-recap-'.$year.'-'.$class.'-'.$payment->number.'.pdf',
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
                'SetTitle' => 'payment-recap-'.$year.'-'.$class.'-'.$payment->number.'.pdf',
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }

    public function actionExport($year, $class, $payment)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // populate data
        $schoolMonths = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $students = null;
        if ($year && $class && $payment) {
            $school = School::findOne(Yii::$app->session->get('school_id'));
            $payment = SchoolPayment::find()->where(['number' => $payment])->one();
            $students = Student::find()->where(['year' => $year, 'class' => $class, 'school_id' => Yii::$app->session->get('school_id')])->all();
        }

        // set main title
        $sheet->setCellValue('A1', 'REKAPITULASI PEMBAYARAN SISWA');
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A2', $school->name);
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('B3', 'Jenis Pembayaran');
        $sheet->setCellValue('B4', 'Tahun Ajaran');
        $sheet->setCellValue('B5', 'Kelas');
        $sheet->setCellValue('C3', ': '.$payment->name);
        $sheet->setCellValue('C4', ': '.$year);
        $sheet->setCellValue('C5', ': '.$class);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(TRUE);
        $sheet->getStyle('A2')->getFont()->setBold(TRUE);

        // set title
        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'NIS');
        $sheet->setCellValue('C7', 'NAMA SISWA');
        $sheet->setCellValue('D7', 'TAGIHAN');
        $sheet->setCellValue('E7', 'BAYAR');
        $sheet->setCellValue('F7', 'KEKURANGAN');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getRowDimension('5')->setRowHeight(30);

        $column = 6;
        $row = 7;

        // styling
        $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('154360');
        $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)
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
        $row = 7;
        $no = 0;
        $totalBayar = 0;
        $totalSisa = 0;

        foreach ($students as $student) {
            $totalBayar = 0;
            $totalSisa = 0;

            // get invoices
            $invoice = Invoice::find()->where(['student_id' => $student->id, 'school_payment_id' => $payment->id])->one();
            if ($invoice) {
                $column = 9;
                $row++;
                $no++;
                // count cash and transfer
                $totalBayar += $invoice->amount;
                $totalSisa += $invoice->paid;
                $sheet->getStyle('D', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('E', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('A' . $row, ($no));
                $sheet->setCellValue('B' . $row, $student->nisn);
                $sheet->setCellValue('C' . $row, $student->name);
                $sheet->setCellValue('D' . $row, $invoice->amountRupiah);
                $sheet->setCellValue('E' . $row, $invoice->paidAmountRupiah);
                $sheet->setCellValue('F' . $row, Strings::getRupiah($invoice->amount - $invoice->paid_amount));

            }
        }

        $row++;
        $sheet->getStyle('A', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('A'.$row, 'TOTAL');
        $sheet->setCellValue('E'.$row, $totalBayar);
        $sheet->setCellValue('F'.$row, $totalSisa);
        $sheet->mergeCells('A'.$row.':'.'D'.$row);

        $filename = 'payment-recap-'.$year.'-'.$class.'-'.$payment->number. '.xlsx';

        ob_clean(); //Emptying the cache
        // header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        unset($objPHPExcel);
        // most important code (exit)
        exit();
    }

}
