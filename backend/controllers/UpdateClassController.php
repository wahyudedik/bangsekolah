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
use yii\web\Response;
use yii\helpers\Json;

class UpdateClassController extends \yii\web\Controller
{
    public function actionIndex($year = null, $class = null)
    {
        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();
        $years = Year::find()->where(['status' => 'ACTIVE'])->all();
        $students = Student::find()->where([
            'school_id' => Yii::$app->session->get('school_id'), 
            'status' => 'ACTIVE',
            'year' => $year,
            'class' => $class
        ])->all();

        return $this->render('index', [
            'year' => $year,
            'class' => $class,
            'years' => $years,
            'classes' => $classes,
            'students' => $students,
        ]);
    }

    public function actionCreate($fromYear = null)
    {
        if (isset($_POST['submit'])) {
            // filter year
            if ($fromYear) {
                $min_year = substr($fromYear, 0, 4);
                $max_year = substr($_POST['next-year'], 0, 4);
                if ($min_year > $max_year) {
                    Yii::$app->session->setFlash('danger', 'Tahun ajaran pindah kelas tidak valid');
                    return $this->redirect(['update-class/index']);
                }
            }

            if (!empty( $_POST['student'])){
                $students = $_POST['student'];
            } else {
                Yii::$app->session->setFlash('danger', 'Pilih atau centang siswa yang akan dipindah kelasnya');
                return $this->redirect(['update-class/index']);
            }
            $year = $_POST['next-year'];
            $class = $_POST['next-class'];

            if ($year == '' || $class == ''){
                Yii::$app->session->setFlash('danger', 'Tahun dan kelas tidak boleh kosong');
                return $this->redirect(['update-class/index']);
            }
            
            $saved = 0;
            foreach ($students as $student_id) {
                $student = Student::findOne($student_id);
                if ($student) {
                    if ($class == 'lulus') {
                        $student->status = 'GRADUATED';
                        $student->year = $year;
                    } else {
                        $student->year = $year;
                        $student->class = $class;
                    }
                    if ($student->save()) {
                        $saved++;
                    }

                    // cek invoice masing-masing siswa
                    foreach ($student->invoices as $inv) {
                        if (!empty($_POST['inv_'.$inv->id])) {
                            if ($inv->amount <> $_POST['inv_'.$inv->id]) {
                                // return $_POST['inv_'.$inv->id];
                                if ($inv->paid_amount <= $_POST['inv_'.$inv->id]) {
                                    $inv->amount = $_POST['inv_'.$inv->id];
                                    $inv->save();
                                }
                            }
                        }
                    }
                }
            }

            if ($saved > 0) {
                Yii::$app->session->setFlash('success', $saved.' siswa berhasil pindah kelas');
            } else {
                Yii::$app->session->setFlash('danger', 'Proses pindah kelas gagal');
            }
            return $this->redirect(['student/index', 'class' => $class]);
        }
    }
}
