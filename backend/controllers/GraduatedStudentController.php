<?php

namespace backend\controllers;

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

/**
 * GraduatedStudentController implements the CRUD actions for Student model.
 */
class GraduatedStudentController extends Controller
{

    /**
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex($search = null, $year = null)
    {
        \common\helpers\Sidebar::setActiveMenu('graduated-student', 'index');

        $query = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'GRADUATED']);
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

    private function populateDataExcel($fileName, $sheet = 0)
    {

        // populate array for data validation
        $arrClass = [];
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        foreach ($schoolClasses as $schoolClass) {
            $arrClass[] = $schoolClass->name;
        }
        $arrYear = [];
        $years = Year::find()->all();
        foreach ($years as $year) {
            $arrYear[] = $year->name;
        }

        // PHP Excel init        
        $phpexcel = \PHPExcel_IOFactory::load($fileName);
        $worksheet = $phpexcel->getSheet($sheet);
        $dataExcel = $worksheet->toArray();
        $error = null;
        $itemArray = [];
        $row_number = 0;
        if ($sheet == 0) {
            foreach ($dataExcel as $rowExcelData) {
                if ($row_number++ > 0 && $rowExcelData[0] !== null) {
                    // check nis student
                    $student = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'),'nis' => trim((string)$rowExcelData[0])])->one();
                    if ($student) {
                        $error = 'NIS siswa sudah ada '.$student->nis;
                        break;
                    }

                    // check gender student
                    $gender = trim((string)$rowExcelData[3]);
                    if ($gender !== 'L' && $gender !== 'P') {
                        $error = 'Jenis kelamin siswa tidak valid. Gunakan L/P untuk jenis kelamin';
                        break;
                    }

                    // validasi data
                    $class = trim((string)$rowExcelData[4]);
                    $year = trim((string)$rowExcelData[5]);
                    if (in_array($class, $arrClass) && in_array($year, $arrYear)) {
                        $itemArray[] = [
                            'nis' => trim((string)$rowExcelData[0]),
                            'nisn' => trim((string)$rowExcelData[1]),
                            'name' => trim((string)$rowExcelData[2]),
                            'gender' => trim((string)$rowExcelData[3]),
                            'class' => trim((string)$rowExcelData[4]),
                            'year' => trim((string)$rowExcelData[5]),
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
