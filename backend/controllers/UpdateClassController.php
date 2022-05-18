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

    public function actionCreate($fromYear = null, $fromClass = null)
    {
        if (isset($_POST['submit'])) {
            if ($fromYear == null || $fromClass == null) {
                Yii::$app->session->setFlash('danger', 'Isi "Dari Kelas" terlebih dahulu');
                return $this->redirect(['update-class/index']);
            }

            if (!empty( $_POST['student'])){
                $students = $_POST['student'];
            } else {
                Yii::$app->session->setFlash('danger', 'Pilih siswa terlebih dahulu');
                return $this->redirect(['update-class/index']);
            }
            $year = $_POST['next-year'];
            $class = $_POST['next-class'];
            

            if ($year == '' || $class == ''){
                Yii::$app->session->setFlash('danger', 'Tahun dan kelas tidak boleh kosong');
                return $this->redirect(['update-class/index']);
            }
            
            $i = 0;
            $saved = 0;
            foreach ($students as $student_id) {
                //$amount = $amounts[$i];

                $student = Student::findOne($student_id);
                if ($student) {
                    if ($class == 'lulus') {
                        $student->status = 'NONACTIVE';
                        $student->year = $year;
                    } else {
                        $student->year = $year;
                        $student->class = $class;
                    }
                    $student->save();
                    $saved++;
                }
            }

            if ($saved > 0) {
                Yii::$app->session->setFlash('success', $saved.' siswa berhasil naik kelas');
            } else {
                Yii::$app->session->setFlash('danger', 'Proses naik kelas gagal');
            }
            return $this->redirect(['student/index', 'class' => $class]);
        }
    }
}
