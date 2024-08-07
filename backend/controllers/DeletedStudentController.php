<?php

namespace backend\controllers;

use common\helpers\Strings;
use common\models\School;
use Yii;
use common\models\Student;
use common\models\SchoolMonth;
use common\models\SchoolClass;
use common\models\Year;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * DeletedStudentController implements the CRUD actions for Student model.
 */
class DeletedStudentController extends Controller
{

    /**
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex($search = null, $year = null)
    {
        \common\helpers\Sidebar::setActiveMenu('deleted-student', 'index');

        $query = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'NONACTIVE']);
        $years = Year::find()->all();

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
        if ($year !== null) {
            if ($year !== 'ALL') {
                $query->andFilterWhere([
                    'and',
                    ['year' => $year],
                ]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'search' => $search,
            'year' => $year,
            'years' => $years,
        ]);
    }

    /**
     * Displays a single Student model.
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

    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // private function populateDataExcel($fileName, $sheet = 0)
    // {

    //     // populate array for data validation
    //     $arrClass = [];
    //     $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
    //     foreach ($schoolClasses as $schoolClass) {
    //         $arrClass[] = $schoolClass->name;
    //     }
    //     $arrYear = [];
    //     $years = Year::find()->all();
    //     foreach ($years as $year) {
    //         $arrYear[] = $year->name;
    //     }

    //     // PHP Excel init        
    //     $phpexcel = \PHPExcel_IOFactory::load($fileName);
    //     $worksheet = $phpexcel->getSheet($sheet);
    //     $dataExcel = $worksheet->toArray();
    //     $error = null;
    //     $itemArray = [];
    //     $row_number = 0;
    //     if ($sheet == 0) {
    //         foreach ($dataExcel as $rowExcelData) {
    //             if ($row_number++ > 0 && $rowExcelData[0] !== null) {
    //                 // check nis student
    //                 $student = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'nis' => trim((string)$rowExcelData[0])])->one();
    //                 if ($student) {
    //                     $error = 'NIS siswa sudah ada ' . $student->nis;
    //                     break;
    //                 }

    //                 // check gender student
    //                 $gender = trim((string)$rowExcelData[3]);
    //                 if ($gender !== 'L' && $gender !== 'P') {
    //                     $error = 'Jenis kelamin siswa tidak valid. Gunakan L/P untuk jenis kelamin';
    //                     break;
    //                 }

    //                 // validasi data
    //                 $class = trim((string)$rowExcelData[4]);
    //                 $year = trim((string)$rowExcelData[5]);
    //                 if (in_array($class, $arrClass) && in_array($year, $arrYear)) {
    //                     $itemArray[] = [
    //                         'nis' => trim((string)$rowExcelData[0]),
    //                         'nisn' => trim((string)$rowExcelData[1]),
    //                         'name' => trim((string)$rowExcelData[2]),
    //                         'gender' => trim((string)$rowExcelData[3]),
    //                         'class' => trim((string)$rowExcelData[4]),
    //                         'year' => trim((string)$rowExcelData[5]),
    //                     ];
    //                 } else {
    //                     $error = 'Data tidak valid pada baris ke ' . $row_number;
    //                     break;
    //                 }
    //             }
    //         }
    //     }
    //     @unlink($fileName);
    //     if ($error != NULL) {
    //         return [
    //             'status' => 'ERROR',
    //             'data' => $error
    //         ];
    //     } else {
    //         return [
    //             'status' => 'OK',
    //             'data' => $itemArray
    //         ];
    //     }
    // }

    public function actionExport( $year = null , $search = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // populate data
        $school_id = Yii::$app->session->get('school_id');
        $school = School::findOne($school_id);

        $query = Student::find()
            ->where(['status' => "NONACTIVE"])
            ->andWhere(['school_id' => $school_id]);

        if ($search) {
            $query->andFilterWhere(['and', ['nisn' => $search]]);
        }
        if ($year) {
            $query->andFilterWhere(['and', ['year' => $year]]);
        }

        $deleted_student = $query->orderBy(['id' => SORT_DESC])->all();
        // set main title
        $sheet->setCellValue('A1', 'DELETED STUDENT');
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A2', strtoupper($school->name));
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(TRUE);
        $sheet->getStyle('A2')->getFont()->setBold(TRUE);
        $sheet->getStyle('A3')->getFont()->setBold(TRUE);
        $sheet->mergeCells('A4:I4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        // set title
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NIS');
        $sheet->setCellValue('C5', 'NAMA');
        $sheet->setCellValue('D5', 'KELAS');
        $sheet->setCellValue('E5', 'JENIS KELAMIN');
        $sheet->setCellValue('F5', 'TAHUN AJARAN');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        $column = 6;
        $row = 5;

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
        foreach ($deleted_student as $del_student) {
            $column = 6;
            $row++;
            $no++;
            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($no));
            $sheet->setCellValue('B' . $row, ($del_student->nis));
            $sheet->setCellValue('C' . $row, $del_student->name);
            $sheet->setCellValue('D' . $row, $del_student->class);
            $sheet->setCellValue('E' . $row, $del_student->gender);
            $sheet->setCellValue('F' . $row, $del_student->year);

        }
        $row++;
        $sheet->getStyle('A', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I', $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A', $row)->getFont()->setBold(TRUE);

        $filename = 'deleted-student-' .  $school->name. '.xlsx';

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
