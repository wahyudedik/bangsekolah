<?php

namespace backend\controllers;

use Yii;
use common\models\Presence;
use common\models\Student;
use common\models\SchoolClass;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PresenceRecapController implements the CRUD actions for Presence model.
 */
class PresenceRecapController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => '@',
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Presence models.
     * @return mixed
     */
    public function actionIndex($startdate = null, $enddate = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('presence-recap', 'index');

        if ($startdate == null && $enddate == null) {
            $startdate = date('Y-m-d', strtotime('-1 week'));
            $enddate = date('Y-m-d');
        }

        // Mengonversi tanggal ke timestamp
        $start_timestamp = strtotime($startdate);
        $end_timestamp = strtotime($enddate);

        // Menghitung selisih hari
        $totalHari = (($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;

        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        return $this->render('index', [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'classes' => $classes,
            'class' => $class,
            'totalHari' => $totalHari,
        ]);
    }
}
