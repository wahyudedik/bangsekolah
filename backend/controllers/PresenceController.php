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
 * PresenceController implements the CRUD actions for Presence model.
 */
class PresenceController extends Controller
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
                        'actions' => ['save'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'save' && Yii::$app->getRequest()->getIsPost()) {
            // Menonaktifkan validasi CSRF untuk tindakan save-presence dengan metode POST
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Presence models.
     * @return mixed
     */
    public function actionIndex($startdate = null, $enddate = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('presence', 'index');

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

    /**
     * Displays a single Presence model.
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
     * Creates a new Presence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Presence();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Presence model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Presence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Presence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Presence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Presence::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSave()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        // Menerima data presensi dari POST request
        // $student_id = Yii::$app->request->post('student_id');
        $pin = Yii::$app->request->post('pin');
        $date = Yii::$app->request->post('date');
        $check_in = Yii::$app->request->post('check_in');
        $check_out = Yii::$app->request->post('check_out');

        // Memeriksa apakah salah satu parameter kosong
        if (empty($pin) || empty($date) || empty($check_in) || empty($check_out)) {
            // Mengembalikan pesan JSON jika ada parameter yang kosong
            return ['status' => 400, 'message' => 'Parameter tidak lengkap'];
        }

        // mencari data siswa
        $student = Student::find()->where(['finger_code' => $pin])->one();
        if (!$student) {
            return ['status' => '404', 'message' => 'Siswa dengan kode ' . $pin . ' tidak ditemukan.'];
        }
        // $student_id = $student->id;

        // Mengecek apakah data presensi sudah ada di tabel Presence
        $presence = Presence::find()
            ->where(['student_id' => $student->id, 'date' => $date])
            ->one();

        if (!$presence) {
            // Jika data presensi belum ada, membuat row baru
            $presence = new Presence();
            $presence->school_id = $student->school_id;
            $presence->student_id = $student->id;
            $presence->class = $student->class;
            $presence->date = $date;
        }

        // Mengupdate check_in dan check_out
        $presence->check_in = $check_in;
        $presence->check_out = $check_out;

        // Menyimpan data presensi
        if ($presence->save()) {
            return ['status' => 200, 'message' => 'Data presensi berhasil disimpan.'];
        } else {
            return ['status' => 500, 'message' => 'Gagal menyimpan data presensi.'];
        }
    }
}
