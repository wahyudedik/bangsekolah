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
                    [
                        'actions' => ['index'],
                        'allow' => '@',
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

        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();

        // query
        $school_id = Yii::$app->session->get('school_id');
        $query = Presence::find()->where(['school_id' => $school_id])->orderBy(['id' => SORT_DESC]);
        if ($startdate && $enddate) {
            $query->andFilterWhere([
                'or',
                ['>=', 'date(check_in)', $startdate],
                ['>=', 'date(check_out)', $enddate],
            ]);
        }
        if ($class) {
            $query->andFilterWhere([
                'or',
                ['like', 'class', $class],
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'classes' => $classes,
            'class' => $class,
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

        // $request = Yii::$app->request;
        // $data = $request->getBodyParams();
        $rawData = Yii::$app->request->getRawBody();
        $data = json_decode($rawData, true);

        // Pastikan data yang diperlukan ada
        if (!is_array($data) || empty($data)) {
            return $this->asJson(['status' => 400, 'message' => 'Data tidak lengkap']);
        }

        $successCount = 0;

        foreach ($data as $item) {
            // Pastikan setiap elemen array memiliki properti 'id' dan 'timestamp'
            if (!isset($item['id']) || !isset($item['timestamp'])) {
                continue; // Lewatkan data yang tidak lengkap
            }

            // Lakukan operasi simpan data ke database atau proses lainnya
            $id = $item['id'];
            $timestamp = $item['timestamp'];
            $date = date('Y-m-d', strtotime($timestamp));

            // mencari data siswa
            $student = Student::find()->where(['finger_code' => $id])->one();
            if (!$student) {
                return ['status' => 404, 'message' => 'Siswa dengan kode ' . $id . ' tidak ditemukan.'];
            }

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
            if ($presence->check_in == null) {
                $presence->check_in = $timestamp;
            } else {
                $presence->check_out = $timestamp;
            }

            // Menyimpan data presensi
            if ($presence->save()) {
                $successCount++;
            }
        }

        return ['status' => 200, 'message' => "$successCount data berhasil disimpan"];
    }
}
