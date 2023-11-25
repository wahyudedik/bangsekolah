<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Event;
use common\models\School;
use common\models\SchoolClass;
use common\models\EventStudent;
use common\models\EventCategory;
use common\models\Student;
use common\helpers\Strings;

/**
 * PresenceRecapController implements the CRUD actions for PresenceRecap model.
 */
class PresenceRecapController extends Controller
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
     * Lists all PresenceRecap models.
     * @return mixed
     */
    public function actionIndex($startdate = null, $enddate = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('presence-recap', 'index');

        if ($startdate == null && $enddate == null) {
            $startdate = date('Y-m-d', strtotime('-1 week'));
            $enddate = date('Y-m-d');
        }

        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        return $this->render('index', [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'classes' => $classes,
            'class' => $class,
        ]);
    }

    /**
     * Dalam proses 
     * 
     * 
     */
    public function actionExportExcel($startdate, $enddate, $class)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // populate data

        // set main title
        $sheet->setCellValue('A1', 'REKAPITULASI PRESENSI');
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A2', 'KELAS ' . $class . ' PERIODE ' . date('d M Y', strtotime($startdate)) . ' s/d ' . date('d M Y', strtotime($enddate)));
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(TRUE);
        $sheet->getStyle('A2')->getFont()->setBold(TRUE);
        $sheet->getStyle('A3')->getFont()->setBold(TRUE);

        // set title
        $sheet->setCellValue('A4', 'NO');
        $sheet->setCellValue('B4', 'NAMA LENGKAP');
        $schoolClass = SchoolClass::find()->where(['name' => $class])->one();
        $eventCategories = EventCategory::find()->all();
        $alphabet = 67;
        foreach ($eventCategories as $category) {
            $events = Event::find()
                ->where(['>=', 'date(date_start)', $startdate])
                ->andWhere(['<=', 'date(date_start)', $enddate])
                ->andWhere(['event_category_id' => $category->id])
                ->andWhere(['school_class_id' => $schoolClass->id])
                ->andWhere(['status' => 'ACTIVE'])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            $sheet->setCellValue($this->generateChar($alphabet) . '4', $category->name);
            if (count($events) > 0) {
                $alphabet += count($events);
            } else {
                $alphabet++;
            }
        }
        // title baris 2
        $sheet->setCellValue($this->generateChar($alphabet) . '4', 'JUMLAH');
        $alphabet += 3;
        $sheet->setCellValue($this->generateChar($alphabet++) . '4', '% KEHADIRAN');
        $sheet->setCellValue($this->generateChar($alphabet++) . '4', 'KETERANGAN');
        $alphabet = 67;
        foreach ($eventCategories as $category) {
            $events = Event::find()
                ->where(['>=', 'date(date_start)', $startdate])
                ->andWhere(['<=', 'date(date_start)', $enddate])
                ->andWhere(['event_category_id' => $category->id])
                ->andWhere(['school_class_id' => $schoolClass->id])
                ->andWhere(['status' => 'ACTIVE'])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            if (count($events) > 0) {
                foreach ($events as $event) {
                    $sheet->setCellValue($this->generateChar($alphabet) . '5', date('d', strtotime($event->date_start)));
                    $sheet->getStyle($this->generateChar($alphabet) . '5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $alphabet++;
                }
            } else {
                $alphabet++;
            }
        }
        $sheet->setCellValue($this->generateChar($alphabet++) . '5', 'H+0');
        $sheet->getStyle($this->generateChar($alphabet) . '5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue($this->generateChar($alphabet++) . '5', 'I');
        $sheet->getStyle($this->generateChar($alphabet) . '5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue($this->generateChar($alphabet++) . '5', 'A');
        $sheet->getStyle($this->generateChar($alphabet) . '5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $column = ($alphabet + 6) - 65;

        // warna header
        $row = 4;
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('154360');
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2C3E50');
        $arrStyle = ['font'  => ['bold'  => true, 'color' => ['rgb' => 'FFFFFF'],]];
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)->applyFromArray($arrStyle);
        // warna header
        $row = 5;
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('154360');
        $sheet->getStyleByColumnAndRow(0, $row, ($column - 1), $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('2C3E50');
        $arrStyle = ['font'  => ['bold'  => true, 'color' => ['rgb' => 'FFFFFF'],]];
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
        $row = 6;
        $i = 1;
        $students = Student::find()->where(['class' => $class, 'school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $totalPersentase = 0;
        $totalSiswa = 0;
        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $student->name);
            $totalSiswa++;
            $alphabet = 67;

            // kolom event student
            $jumlahH = 0;
            $jumlahI = 0;
            $jumlahA = 0;
            $totalEvent = 0;
            foreach ($eventCategories as $category) {
                $events = Event::find()
                    ->where(['>=', 'date(date_start)', $startdate])
                    ->andWhere(['<=', 'date(date_start)', $enddate])
                    ->andWhere(['event_category_id' => $category->id])
                    ->andWhere(['school_class_id' => $schoolClass->id])
                    ->andWhere(['status' => 'ACTIVE'])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                $totalEvent += count($events);

                if (count($events) > 0) {
                    foreach ($events as $event) {
                        $eventStudent = EventStudent::find()->where(['event_id' => $event->id, 'student_id' => $student->id])->one();
                        if ($eventStudent) {
                            if ($eventStudent->status == 'PRESENT') {
                                $sheet->setCellValue($this->generateChar($alphabet) . $row, 'H');
                                $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                $alphabet++;
                                $jumlahH++;
                            } else {
                                if ($eventStudent->note == 'IZIN') {
                                    $sheet->setCellValue($this->generateChar($alphabet) . $row, 'I');
                                    $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $alphabet++;
                                    $jumlahI++;
                                } else {
                                    $sheet->setCellValue($this->generateChar($alphabet) . $row, '');
                                    $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $alphabet++;
                                    $jumlahA++;
                                }
                            }
                        } else {
                            $sheet->setCellValue($this->generateChar($alphabet) . $row, '');
                            $alphabet++;
                        }
                    }
                } else {
                    $sheet->setCellValue($this->generateChar($alphabet) . $row, '');
                    $alphabet++;
                }
            }

            // kolom jumlah
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $jumlahH);
            $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $jumlahI);
            $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $jumlahA);
            $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            // persentase
            if ($totalEvent > 0) {
                $persentase = round((($jumlahH + $jumlahI) / $totalEvent) * 100);
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
            $sheet->getStyle($this->generateChar($alphabet) . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($this->generateChar($alphabet) . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $persentase);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $student->parent_name);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $student->phone);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $student->address_type);
            $sheet->setCellValue($this->generateChar($alphabet++) . $row, $student->address);

            $row++;
            $i++;
        }

        for ($i = 65; $i <= ($alphabet + 5); $i++) {
            if ($i < 80) {
                $sheet->getColumnDimension(chr($i))->setAutoSize(true);
            }
        }

        if ($totalSiswa > 0) {
            $percentage = round($totalPersentase / ($totalSiswa * 100) * 100);
        } else {
            $percentage = 0;
        }
        $sheet->setCellValue('B' . ($row + 1), 'Kehadiran rata-rata peserta:');
        $sheet->setCellValue('C' . ($row + 1), $percentage . '%');
        $sheet->getStyle('C' . ($row + 1))->getFont()->setSize(30);


        $filename = 'presence-recap-' . $startdate . '-' . $enddate . '.xlsx';

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

    private function generateChar($column)
    {
        $character = '';
        if ($column > 90) {
            $character = 'A' . chr($column - 26);
        } else {
            $character = chr($column);
        }
        return $character;
    }
}
