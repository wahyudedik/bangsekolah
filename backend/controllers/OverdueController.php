<?php

namespace backend\controllers;

use Yii;

use common\models\SchoolClass;
use common\models\SchoolPayment;
use common\models\SchoolMonth;
use common\models\Year;
use common\models\Student;

use yii\web\Controller;

/**
 * OverdueController implements the CRUD actions for Transaction model.
 */
class OverdueController extends Controller
{

    public function actionIndex($year = null, $class = null, $payment = null, $month = null)
    {
        \common\helpers\Sidebar::setActiveMenu('overdue', 'index');

        $years = Year::find()->where(['status' => 'ACTIVE'])->all();
        $schoolPayments = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'payment_type_id' => '1', 'status' => 'ACTIVE'])->all();
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolMonths = SchoolMonth::find()->where(['status' => 'ACTIVE'])->all();

        $students = null;
        if ($year && $class && $payment) {
            $payment = SchoolPayment::find()->where(['number' => $payment])->one();
            $students = Student::find()->where(['year' => $year, 'class' => $class, 'school_id' => Yii::$app->session->get('school_id')])->all();
        }

        return $this->render('index', [
            'class' => $class,
            'year' => $year,
            'payment' => $payment,
            'month' => $month,
            'years' => $years,
            'schoolPayments' => $schoolPayments,
            'schoolClasses' => $schoolClasses,
            'schoolMonths' => $schoolMonths,
            'students' => $students,
        ]);
    }

}
