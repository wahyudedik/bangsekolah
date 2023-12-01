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
        \common\helpers\Sidebar::setActiveMenu('presence-recap', 'index');

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
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // populate data
        $school_id = Yii::$app->session->get('school_id');
        $school = School::findOne($school_id);

        // Mengonversi tanggal ke timestamp
        $start_timestamp = strtotime($startdate);
        $end_timestamp = strtotime($enddate);

        // Menghitung selisih hari
        $totalHari = (($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;

        // set main title
        $sheet->setCellValue('A1', 'REKAPITULASI PRESENSI HARIAN');
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A2', strtoupper($school->name));
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A3', strtoupper('KELAS ' . $class));
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(TRUE);
        $sheet->getStyle('A2')->getFont()->setBold(TRUE);
        $sheet->getStyle('A3')->getFont()->setBold(TRUE);
        $sheet->setCellValue('A4', 'Tanggal : ' . Strings::getFullDate($startdate) . ' s/d ' . Strings::getFullDate($enddate));
        $sheet->mergeCells('A4:F4');

        // set title
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NAMA SISWA');
        $sheet->setCellValue('C5', 'MASUK');
        $sheet->setCellValue('D5', 'ABSEN');
        $sheet->setCellValue('E5', 'JUMLAH_HARI');
        $sheet->setCellValue('F5', '%KEHADIRAN');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getRowDimension('5')->setRowHeight(30);

        $column = 6;
        $row = 5;

        // styling
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('154360');
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)
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
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)->applyFromArray($arrStyle);

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

        $students = Student::find()->where(['class' => $class, 'school_id' => $school_id, 'status' => 'ACTIVE'])->all();
        $totalPersentase = 0;
        foreach ($students as $student) {
            $column = 6;
            $row++;
            $no++;
            $jumlahMasuk = 0;
            $jumlahAbsen = 0;

            // menghitung total masuk
            $jumlahMasuk = Presence::find()
                ->where(['school_id' => $school_id, 'class' => $class])
                ->andWhere(['student_id' => $student->id])
                ->andWhere(['>=', 'date', $startdate])
                ->andWhere(['<=', 'date', $enddate])
                ->count();
            $jumlahAbsen = $totalHari - $jumlahMasuk;

            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($no));
            $sheet->setCellValue('B' . $row, $student->name);
            $sheet->setCellValue('C' . $row, $jumlahMasuk);
            $sheet->setCellValue('D' . $row, $jumlahAbsen);
            $sheet->setCellValue('E' . $row, $totalHari);
            if ($totalHari > 0) {
                $persentase = round(($jumlahMasuk / $totalHari) * 100);
            } else {
                $persentase = 0;
            }
            $sheet->setCellValue('F' . $row, $persentase . '%');
            $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)
                ->applyFromArray($style_row);
        }
        $row++;

        $filename = 'rekapitulasi-presensi-' . $startdate . '-' . $enddate . '.xlsx';

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
