<?php

namespace backend\controllers;

use Yii;

use common\models\SchoolClass;
use common\models\SchoolPayment;
use common\models\SchoolMonth;
use common\models\Year;
use common\models\Student;
use common\models\School;
use common\models\Transaction;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use kartik\mpdf\Pdf;

/**
 * PaymentRecapController implements the CRUD actions for Transaction model.
 */
class PaymentRecapController extends Controller
{

    public function actionIndex($startdate = null, $enddate = null, $year = null, $class = null, $payment = null, $method = null)
    {
        \common\helpers\Sidebar::setActiveMenu('payment-recap', 'index');

        $school_id = Yii::$app->session->get('school_id');
        $years = Year::find()->where(['status' => 'ACTIVE'])->all();
        $schoolPayments = SchoolPayment::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        $schoolMonths = SchoolMonth::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = Transaction::find()
            ->joinWith(['student', 'schoolPayment'])
            ->where(['student.school_id' => Yii::$app->session->get('school_id')]);

        if ($startdate) { $query->andFilterWhere(['and', ['>=', 'DATE(transaction.date)', $startdate]]); }
        if ($enddate) { $query->andFilterWhere(['and', ['<=', 'DATE(transaction.date)', $enddate]]); }
        if($year) { $query->andFilterWhere(['student.year' => $year]); }
        if($class) { $query->andFilterWhere(['student.class' => $class]); }
        if($payment) { $query->andFilterWhere(['or', ['like', 'school_payment.number', $payment]]); }
        if($method) { $query->andFilterWhere(['or', ['like', 'payment_method', $method]]); }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'year' => $year,
            'class' => $class,
            'payment' => $payment,
            'method' => $method,
            'years' => $years,
            'schoolPayments' => $schoolPayments,
            'schoolClasses' => $schoolClasses,
            'schoolMonths' => $schoolMonths,
            // 'students' => $students,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($startdate, $enddate, $year, $class, $payment, $method)
    {
        $school_id = Yii::$app->session->get('school_id');
        $school = School::findOne($school_id);

        $query = Transaction::find()
            ->joinWith(['student', 'schoolPayment'])
            ->where(['student.school_id' => $school_id]);

        if ($startdate) { $query->andFilterWhere(['and', ['>=', 'DATE(transaction.date)', $startdate]]); }
        if ($enddate) { $query->andFilterWhere(['and', ['<=', 'DATE(transaction.date)', $enddate]]); }
        if($year) { $query->andFilterWhere(['student.year' => $year]); }
        if($class) { $query->andFilterWhere(['student.class' => $class]); }
        if($payment) { $query->andFilterWhere(['or', ['like', 'school_payment.number', $payment]]); }
        if($method) { $query->andFilterWhere(['or', ['like', 'payment_method', $method]]); }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        $content = $this->renderPartial('@app/views/pdf-template/payment-recap', [
            'dataProvider' => $dataProvider,
            'school' => $school,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'class' => $class,
            'year' => $year,
        ]);

        // return $content;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'payment-recap-'.$startdate.'-'.$enddate.'-'.$class.'-'.$payment.'-'.$method.'.pdf',
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
                'SetTitle' => 'payment-recap-'.$startdate.'-'.$enddate.'-'.$class.'-'.$payment.'-'.$method.'.pdf',
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }

}
