<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSchool;
use common\models\Teacher;
use common\models\SchoolClass;
use common\models\Year;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\helpers\Strings;
use yii\web\UploadedFile;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
{

    /**
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex($search = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('teacher', 'index');

        $query = Teacher::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE']);
        $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();

        // force fill default class
        /*
        if (!$class) {
            foreach ($classes as $cls) {
                $class = $cls->name;
                break;
            }
        }
        */

        if ($search !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'nik', $search],
                ['like', 'name', $search],
            ]);
        }
        if ($class !== null) {
            if ($class !== 'ALL') {
                $query->andFilterWhere([
                    'and',
                    ['class' => $class],
                ]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'search' => $search,
            'class' => $class,
            'classes' => $classes,
        ]);
    }

    /**
     * Displays a single Teacher model.
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
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $school_id = Yii::$app->session->get('school_id');
        $model = new Teacher();

        if ($model->load(Yii::$app->request->post())) {

            // check nik guru harus unik dalam satu sekolah
            $checkNik = Teacher::find()->where(['nik' => $model->nik, 'school_id' => $school_id])->one();
            if ($checkNik) {
                Yii::$app->session->setFlash('danger', 'NIK ' . $model->nik . ' sudah ada di sekolah Anda');
                return $this->redirect(['create']);
            }

            $model->school_id = $school_id;
            $model->status = 'ACTIVE';
            $model->nik = strtoupper(Strings::sluggify($model->nik));

            if ($model->save()) {

                // membuat user untuk guru baru
                $user = new User();
                $user->username = $model->nik;
                $user->password_hash = Yii::$app->security->generatePasswordHash($model->nik);
                $user->name = $model->name;
                $user->role_id = 5;
                if ($user->save()) {
                    $userSchool = new UserSchool();
                    $userSchool->user_id = $user->id;
                    $userSchool->school_id = $school_id;
                    $userSchool->save();

                    $model->user_id = $user->id;
                    $model->save();
                }

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['create', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
                        $model = new Teacher();
                        $model->status = 'ACTIVE';
                        $model->school_id = $school_id;
                        $model->nik = strtoupper(Strings::sluggify($item['nik']));
                        $model->name = $item['name'];
                        $model->gender = $item['gender'];
                        $model->class = $item['class'];
                        $model->year = $item['year'];

                        if ($model->save()) {
                            // membuat user untuk guru baru
                            $user = new User();
                            $user->username = $model->nik;
                            $user->password_hash = Yii::$app->security->generatePasswordHash($model->nik);
                            $user->name = $model->name;
                            $user->role_id = 5;
                            if ($user->save()) {
                                $userSchool = new UserSchool();
                                $userSchool->user_id = $user->id;
                                $userSchool->school_id = $school_id;
                                $userSchool->save();

                                $model->user_id = $user->id;
                                $model->save();
                            }

                            $totalItem++;
                        } else {
                            foreach ($model->errors as $error) {
                                Yii::$app->session->setFlash('danger', $error[0]);
                            }
                            return $this->render('import');
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', $totalItem . ' guru berhasil diimport');
                return $this->redirect(['index']);
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Teacher model.
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
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 'NONACTIVE';
        $model->save();

        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function populateDataExcel($fileName, $sheet = 0)
    {

        // populate array for data validation
        $arrClass = [];
        $schoolClasses = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->all();
        foreach ($schoolClasses as $schoolClass) {
            $arrClass[] = $schoolClass->name;
        }
        $arrYear = [];
        $years = Year::find()->all();
        foreach ($years as $year) {
            $arrYear[] = $year->name;
        }

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
                    // check NIK teacher
                    $teacher = Teacher::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'nik' => trim((string)$rowExcelData[0])])->one();
                    if ($teacher) {
                        $error = 'NIK guru sudah ada ' . $teacher->nik;
                        break;
                    }

                    // check gender teacher
                    $gender = trim((string)$rowExcelData[2]);
                    if ($gender !== 'L' && $gender !== 'P') {
                        $error = 'Jenis kelamin guru tidak valid. Gunakan L/P untuk jenis kelamin';
                        break;
                    }

                    // validasi data
                    $class = trim((string)$rowExcelData[3]);
                    $year = trim((string)$rowExcelData[4]);
                    // if (in_array($class, $arrClass) && in_array($year, $arrYear)) {
                    // } else {
                    //     $error = 'Data tidak valid pada baris ke ' . $row_number;
                    //     break;
                    // }
                    $itemArray[] = [
                        'nik' => trim((string)$rowExcelData[0]),
                        'name' => trim((string)$rowExcelData[1]),
                        'gender' => $gender,
                        'class' => $class,
                        'year' => $year,
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
