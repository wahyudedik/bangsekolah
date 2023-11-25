<?php

namespace backend\controllers;

use Yii;
use common\models\Teacher;
use common\models\Student;
use common\models\LessonSchedule;
use common\models\LessonScheduleAttendance;
use common\models\LessonScheduleTemplate;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
}
