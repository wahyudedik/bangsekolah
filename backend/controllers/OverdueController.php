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
        $schoolMonths = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

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

    public function actionDownload($year, $class, $payment, $month)
    {
        $schoolMonths = SchoolMonth::find()->where(['status' => 'ACTIVE', 'school_id' => Yii::$app->session->get('school_id')])->all();
        $students = null;
        if ($year && $class && $payment && $month) {
            $payment = SchoolPayment::find()->where(['number' => $payment])->one();
            $students = Student::find()->where(['year' => $year, 'class' => $class, 'school_id' => Yii::$app->session->get('school_id')])->all();
        }

        $content = $this->renderPartial('@app/views/pdf-template/overdue', [
            'month' => $month,
            'payment' => $payment,
            'students' => $students,
            'schoolMonths' => $schoolMonths,
        ]);

        // return $content;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'payment-recap-'.$year.'-'.$class.'-'.$payment->number.'.pdf',
            'format' => [210, 330],
            // 'format' => [148, 210],
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            // 'marginTop' => '10',
            // 'marginLeft' => '15',
            // 'marginRight' => '15',
            'marginTop' => '5',
            'marginLeft' => '5',
            'marginRight' => '5',
            'content' => $content,
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // 'cssFile' => '@app/web/css/surat-penawaran.css',
            'cssInline' => '.border-top{border-top: 1px solid grey;}',
            'options' => [
                'showWatermarkText' => true,
                'showWatermarkImage' => true,
            ],
            'methods' => [
                'SetTitle' => 'payment-recap-'.$year.'-'.$class.'-'.$payment->number.'.pdf',
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }

}
