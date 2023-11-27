<?php

namespace backend\controllers;

use Yii;
use common\models\Teacher;
use common\models\Student;
use common\models\Lesson;
use common\models\SchoolClass;
use common\models\LessonSchedule;
use common\models\LessonScheduleAttendance;
use common\models\LessonScheduleTemplate;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LessonScheduleController implements the CRUD actions for LessonSchedule model.
 */
class LessonScheduleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LessonSchedule models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('lesson-schedule', 'index');

        $dataProvider = new ActiveDataProvider([
            'query' => LessonSchedule::find()->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
        ]);
    }

    /**
     * Displays a single LessonSchedule model.
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

    public function actionSavePresence($id)
    {
        $schedule = LessonSchedule::findOne($id);
        if (!$schedule) {
            Yii::$app->session->setFlash('danger', 'Kelas pelajaran tidak ditemukan');
            return $this->redirect(['index']);
        }

        $scheduleAttendances = null;
        if (!empty($_POST['student'])) {
            $scheduleAttendances = $_POST['student'];
        }

        // reset all presence
        $oldscheduleAttendances = LessonScheduleAttendance::find()->where(['lesson_schedule_id' => $id])->all();
        foreach ($oldscheduleAttendances as $oldscheduleAttendance) {
            $oldscheduleAttendance->status = 'ABSEN';
            $oldscheduleAttendance->note = $_POST['note-' . $oldscheduleAttendance->id];
            $oldscheduleAttendance->save();
        }

        $saved = 0;
        if ($scheduleAttendances) {
            foreach ($scheduleAttendances as $scheduleAttendance) {
                // check if student is already in list
                $scheduleAttendance = LessonScheduleAttendance::findOne($scheduleAttendance);
                if ($scheduleAttendance) {
                    $scheduleAttendance->status = 'MASUK';
                    if ($scheduleAttendance->save()) {
                        $saved++;
                    } else {
                        foreach ($scheduleAttendance->errors as $error) {
                            Yii::$app->session->setFlash('danger', $error[0]);
                            return $this->redirect(['view', 'id' => $id]);
                        }
                    }
                }
            }
        }

        if ($saved > 0) {
            Yii::$app->session->setFlash('success', $saved . ' siswa menghadiri pelajaran');
        } else {
            Yii::$app->session->setFlash('warning', 'Status kehadiran tersimpan. Tidak ada siswayang menghadiri pelajaran');
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Creates a new LessonSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $template = LessonScheduleTemplate::findOne($id);
        $school_id = Yii::$app->session->get('school_id');
        // $user = Yii::$app->user->identity;
        // $teacher = Teacher::find()->where(['user_id' => $user->id])->one();

        // cek apakah kelas pelajaran sudah ada
        $check = LessonSchedule::find()->where([
            'school_id' => $school_id,
            'class' => $template->class,
            'teacher_id' => $template->teacher_id,
            'lesson_id' => $template->lesson_id,
            'date' => date('Y-m-d')
        ])->one();
        if ($check) {
            return $this->redirect(['lesson-schedule/view', 'id' => $check->id]);
        }

        $model = new LessonSchedule();
        $model->school_id = $school_id;
        $model->class = $template->class;
        $model->date = date('Y-m-d');
        $model->hour = $template->hour;
        $model->teacher_id = $template->teacher_id;
        $model->lesson_id = $template->lesson_id;
        $model->status = 'ACTIVE';

        if ($model->save()) {
            // menambahkan semua siswa pada kelas tersebut ke kelas
            $students = Student::find()->where(['school_id' => $school_id, 'class' => $template->class])->all();
            foreach ($students as $student) {
                $attendance = new LessonScheduleAttendance();
                $attendance->lesson_schedule_id = $model->id;
                $attendance->student_id = $student->id;
                $attendance->status = 'ABSEN';
                $attendance->save();
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Gagal buat kelas pelajaran baru');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Updates an existing LessonSchedule model.
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
     * Deletes an existing LessonSchedule model.
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
     * Finds the LessonSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LessonSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LessonSchedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImport()
    {
        $school_id = Yii::$app->session->get('school_id');
        $model = new Teacher();

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

                        $schedule = LessonSchedule::find()
                            ->where(['school_id' => $school_id, 'class' => $item['class'], 'date' => $item['date'], 'teacher_id' => $item['teacher_id'], 'status' => 'ACTIVE'])
                            ->one();
                        if ($schedule) {
                            $scheduleAttendance = LessonScheduleAttendance::find()
                                ->where(['lesson_schedule_id' => $schedule->id, 'student_id' => $item['student_id']])
                                ->one();
                            if ($scheduleAttendance) {
                                $scheduleAttendance->status = $item['status'];
                                $scheduleAttendance->note = $item['note'];
                                if ($scheduleAttendance->save()) {
                                    $totalItem++;
                                } else {
                                    foreach ($model->errors as $error) {
                                        Yii::$app->session->setFlash('danger', $error[0]);
                                    }
                                    return $this->render('import');
                                }
                            }
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

                    // cek kelas
                    $schoolClass = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'name' => $rowExcelData[1], 'status' => 'ACTIVE'])->one();
                    if (!$schoolClass) {
                        $error = 'Kelas ' . $rowExcelData[1] . ' pada baris ' . $row_number . ' tidak ditemukan';
                        break;
                    }

                    // cek mata pelajaran
                    $lesson = Lesson::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'name' => $rowExcelData[2], 'status' => 'ACTIVE'])->one();
                    if (!$lesson) {
                        $error = 'Pelajaran "' . $rowExcelData[2] . '" pada baris ' . $row_number . ' tidak ditemukan';
                        break;
                    }

                    // check NIK teacher
                    $teacher = Teacher::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'nik' => $rowExcelData[3], 'status' => 'ACTIVE'])->one();
                    if ($teacher) {
                        $user_id = Yii::$app->user->identity->id;
                        if ($teacher->user_id !== $user_id) {
                            $error = 'Anda hanya diperbolehkan import presensi Anda sendiri';
                            break;
                        }
                    } else {
                        $error = 'NIK ' . $rowExcelData[3] . ' guru pada baris ' . $row_number . ' tidak ditemukan';
                        break;
                    }

                    // check NIS student
                    $student = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'nis' => $rowExcelData[4], 'status' => 'ACTIVE'])->one();
                    if (!$student) {
                        $error = 'NIS ' . $rowExcelData[4] . ' siswa pada baris ' . $row_number . ' tidak ditemukan';
                        break;
                    }

                    // check kolom status
                    $arrStatus = ['MASUK', 'ABSEN'];
                    if (in_array($rowExcelData[5], $arrStatus)) {
                    } else {
                        $error = 'Kolom status pada baris ' . $row_number . ' tidak valid. Pilihan: MASUK, ABSEN';
                        break;
                    }

                    // check kolom keterangan
                    $arrStatus = ['SAKIT', 'IZIN', 'TANPA KETERANGAN', ''];
                    if (in_array($rowExcelData[6], $arrStatus)) {
                    } else {
                        $error = 'Kolom keterangan pada baris ' . $row_number . ' tidak valid. Pilihan: SAKIT, IZIN, TANPA KETERANGAN';
                        break;
                    }

                    $itemArray[] = [
                        'date' => date('Y-m-d', strtotime($rowExcelData[0])),
                        'class' => $rowExcelData[1],
                        'lesson' => $rowExcelData[2],
                        'teacher_id' => $teacher->id,
                        'student_id' => $student->id,
                        'status' => $rowExcelData[5],
                        'note' => $rowExcelData[6],
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
