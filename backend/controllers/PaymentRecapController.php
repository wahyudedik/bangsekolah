<?php

namespace backend\controllers;

use Yii;

use common\models\SchoolClass;
use common\models\SchoolPayment;
use common\models\Year;
use common\models\Student;

use yii\web\Controller;

/**
 * PaymentRecapController implements the CRUD actions for Transaction model.
 */
class PaymentRecapController extends Controller
{

    public function actionIndex($year = null, $class = null, $payment = null)
    {
        \common\helpers\Sidebar::setActiveMenu('payment-recap', 'index');

        $years = Year::find()->where(['status' => 'ACTIVE'])->all();
        $schoolPayments = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();

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
            'students' => $students,
        ]);
    }

}
