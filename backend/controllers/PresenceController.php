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
use yii\web\UploadedFile;

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
                        'actions' => ['index', 'import'],
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
                if ($presence->check_in !== $timestamp) {
                    $presence->check_out = $timestamp;
                } else {
                    $presence->check_out = null;
                }
            }

            // Menyimpan data presensi
            if ($presence->save()) {
                $successCount++;
            }
        }

        return ['status' => 200, 'message' => "$successCount data berhasil disimpan"];
    }

    public function actionImport()
    {
        $school_id = Yii::$app->session->get('school_id');
        $model = new Presence();

        if ($model->load(Yii::$app->request->post())) {

            $import = UploadedFile::getInstance($model, 'file');
            if (isset($import)) {
                $exploded_name = explode('.', $import->name);
                $extension = end($exploded_name);
                $model->file = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/import/') . $model->file;
                $import->saveAs($path);

                $totalItem = 0;
                $transaction = Yii::$app->db->beginTransaction();

                $result = $this->populateDataExcel($path, 0);
                if ($result['status'] == 'ERROR') {
                    Yii::$app->session->setFlash('danger', $result['data']);
                } else {
                    $data = $result['data'];

                    foreach ($data as $item) {
                        $presence = Presence::find()->where(['school_id' => $school_id, 'date' => $item['date']])->one();
                        if (!$presence) {
                            $student = $item['student'];
                            $presence = new Presence();
                            $presence->school_id = $school_id;
                            $presence->student_id = $student->id;
                            $presence->class = $student->class;
                            $presence->date = $item['date'];
                        }
                        $presence->check_in = $item['date'] . ' ' . $item['check_in'];
                        $presence->check_out = $item['date'] . ' ' . $item['check_out'];
                        if ($presence->save()) {
                            $totalItem++;
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', $totalItem . ' data presensi berhasil diimport');
                return $this->redirect(['index']);
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    private function populateDataExcel($fileName, $sheet = 0)
    {

        // PHP Excel init        
        $phpexcel = \PHPExcel_IOFactory::load($fileName);
        $worksheet = $phpexcel->getSheet($sheet);
        $dataExcel = $worksheet->toArray();
        $error = null;
        $itemArray = [];
        $row_number = 0;
        if ($sheet == 0) {
            foreach ($dataExcel as $rowExcelData) {
                if ($row_number++ > 0 && $rowExcelData[0] !== null) {

                    // check NIS student
                    $student = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'nis' => $rowExcelData[1], 'status' => 'ACTIVE'])->one();
                    if (!$student) {
                        $error = 'NIS ' . $rowExcelData[1] . ' siswa pada baris ' . $row_number . ' tidak ditemukan';
                        break;
                    }

                    // Cek apakah format waktu valid
                    // Coba membuat objek DateTime dari string dengan format H:i
                    $dateTimeCheckIn = \DateTime::createFromFormat('H:i', $rowExcelData[2]);
                    $dateTimeCheckOut = \DateTime::createFromFormat('H:i', $rowExcelData[2]);

                    // Periksa apakah berhasil dan apakah nilai yang diberikan sesuai dengan format H:i
                    if ($dateTimeCheckIn->format('H:i') !== $rowExcelData[2] && $dateTimeCheckOut->format('H:i') !== $rowExcelData[3]) {
                        $error = 'Format waktu checkin dan checkout pada baris ' . $row_number . ' tidak valid';
                        break;
                    }

                    $itemArray[] = [
                        'date' => date('Y-m-d', strtotime($rowExcelData[0])),
                        'student' => $student,
                        'check_in' => $rowExcelData[2],
                        'check_out' => $rowExcelData[3],
                    ];
                }
            }
        }
        @unlink($fileName);
        if ($error != NULL) {
            return [
                'status' => 'ERROR',
                'data' => $error
            ];
        } else {
            return [
                'status' => 'OK',
                'data' => $itemArray
            ];
        }
    }
}
