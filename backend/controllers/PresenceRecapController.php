<?php

namespace backend\controllers;

use Yii;
use common\models\Presence;
use common\models\Student;
use common\models\SchoolClass;
use common\models\School;
use common\helpers\Strings;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * PresenceRecapController implements the CRUD actions for Presence model.
 */
class PresenceRecapController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'export'],
                        'allow' => '@',
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Presence models.
     * @return mixed
     */
    public function actionIndex($startdate = null, $enddate = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('presence', 'index');

        if ($startdate == null && $enddate == null) {
            $startdate = date('Y-m-d', strtotime('-1 week'));
            $enddate = date('Y-m-d');
        }

        // Mengonversi tanggal ke timestamp
        $start_timestamp = strtotime($startdate);
        $end_timestamp = strtotime($enddate);

        // Menghitung selisih hari
        $totalHari = (($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;

        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        return $this->render('index', [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'classes' => $classes,
            'class' => $class,
            'totalHari' => $totalHari,
        ]);
    }

    public function actionExport($startdate, $enddate, $class)
    {
        $jumlahMasuk = 0;
        $jumlahAbsen = 0;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // populate data
        $school_id = Yii::$app->session->get('school_id');
        $students = Student::find()->where(['class' => $class, 'school_id' => $school_id, 'status' => 'ACTIVE'])->all();
        if ($startdate == null && $enddate == null) {
            $startdate = date('Y-m-d', strtotime('-1 week'));
            $enddate = date('Y-m-d');
        }

        $school = School::findOne($school_id);


        // set main title
        $sheet->setCellValue('A1', 'PRESENSI HARIAN');
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A2', strtoupper($school->name));
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(TRUE);
        $sheet->getStyle('A2')->getFont()->setBold(TRUE);
        $sheet->getStyle('A3')->getFont()->setBold(TRUE);
        $sheet->mergeCells('A4:I4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // set title
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NAMA');
        $sheet->setCellValue('C5', 'KEHADIRAN');
        $sheet->setCellValue('F5', '%KEHADIRAN');
        $sheet->setCellValue('G5', 'KETERANGAN');
        $sheet->setCellValue('C6', 'MASUK');
        $sheet->setCellValue('D6', 'ABSEN');
        $sheet->setCellValue('E6', 'JUMLAH HARI');

        $sheet->mergeCells('A5:A6');
        $sheet->mergeCells('B5:B6');
        $sheet->mergeCells('C5:E5');
        $sheet->mergeCells('F5:F6');
        $sheet->mergeCells('G5:G6');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        $column = 7;
        $row = 6;

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
        $row = 6;
        $no = 0;
        $totalSiswa = 0;
        $totalPersentase = 0;
        foreach ($students as $student) {
            $totalSiswa++;
            // Mengonversi tanggal ke timestamp
            $start_timestamp = strtotime($startdate);
            $end_timestamp = strtotime($enddate);
            $totalHari = (($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;
            // menghitung total masuk
            if ($totalHari > 0) {
                $persentase = round(($jumlahMasuk / $totalHari) * 100);
            } else {
                $persentase = 0;
            }
            $totalPersentase += $persentase;
            if ($persentase >= 50) {
                $color = 'BFFCC6'; //green
            } else if ($persentase < 50 && $persentase > 30) {
                $color = 'FFF5BA'; //kuning
            } else {
                $color = 'FFC9DE'; //red
            }
            $jumlahMasuk = Presence::find()
                ->where(['school_id' => $school_id, 'class' => $class])
                ->andWhere(['student_id' => $student->id])
                ->andWhere(['>=', 'date', $startdate])
                ->andWhere(['<=', 'date', $enddate])
                ->count();
            $jumlahAbsen = $totalHari - $jumlahMasuk;

            $column = 7;
            $row++;
            $no++;
            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('G', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($no));
            $sheet->setCellValue('B' . $row, $student->name);
            $sheet->setCellValue('C' . $row, $jumlahMasuk);
            $sheet->setCellValue('D' . $row, $jumlahAbsen);
            $sheet->setCellValue('E' . $row, $totalHari);
            $sheet->setCellValue('F' . $row, $persentase . '%');
            $sheet->setCellValue('G' . $row,'-');
            $sheet->getStyle('F')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
        }

        $row++;
        $sheet->getStyle('A', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('G', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        // $sheet->getStyle('H', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        // $sheet->getStyle('I', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A', $row)->getFont()->setBold(TRUE);

        $filename = 'lesson-schedule-attendance' . $school->name . '.xlsx';

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
