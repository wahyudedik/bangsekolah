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

class UpdateClassController extends \yii\web\Controller
{
    public function actionIndex($year = null, $class = null)
    {
        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();
        $years = Year::find()->all();
        $students = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 
            'year' => $year, 'class' => $class])->all();

        return $this->render('index', [
            'year' => $year,
            'class' => $class,
            'years' => $years,
            'classes' => $classes,
            'students' => $students,
        ]);
    }

}
