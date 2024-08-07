<?php

namespace backend\controllers;

use Yii;
use common\models\Student;
use common\models\SchoolMonth;
use common\models\SchoolClass;
use common\models\Year;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
{

    /**
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex($search = null, $class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('student', 'index');

        $query = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE']);
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
                ['like', 'nis', $search],
                ['like', 'nisn', $search],
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
     * Displays a single Student model.
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
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Student();

        if ($model->load(Yii::$app->request->post())) {
            $model->school_id = Yii::$app->session->get('school_id');
            $model->status = 'ACTIVE';

            if ($model->save()) {
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
        $model = new Student();

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
                        $model = new Student();
                        $model->status = 'ACTIVE';
                        $model->school_id = Yii::$app->session->get('school_id');
                        $model->nis = $item['nis'];
                        $model->nisn = $item['nisn'];
                        $model->name = $item['name'];
                        $model->gender = $item['gender'];
                        $model->class = $item['class'];
                        $model->year = $item['year'];

                        if (!$model->save()) {
                            foreach ($model->errors as $error) {
                                Yii::$app->session->setFlash('danger', $error[0]);
                            }
                            return $this->render('create', [
                                'id' => $model->id
                            ]);
                        }
                        $totalItem++;
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', $totalItem . ' siswa berhasil diimport');
                return $this->redirect(['index']);
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Student model.
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
     * Deletes an existing Student model.
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
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
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
        $phpexcel = IOFactory::load($fileName);
        $worksheet = $phpexcel->getSheet($sheet);
        $dataExcel = $worksheet->toArray();
        $error = null;
        $itemArray = [];
        $row_number = 0;
        if ($sheet == 0) {
            foreach ($dataExcel as $rowExcelData) {
                if ($row_number++ > 0 && $rowExcelData[0] !== null) {
                    // check nis student
                    $nis = trim((string)$rowExcelData[0]);
                    $student = Student::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'nis' => $nis])->one();
                    if ($student) {
                        $error = 'NIS siswa sudah ada ' . $student->nis;
                        break;
                    }

                    // check gender student
                    $gender = trim((string)$rowExcelData[3]);
                    if ($gender !== 'L' && $gender !== 'P') {
                        $error = 'Jenis kelamin siswa tidak valid. Gunakan L/P untuk jenis kelamin';
                        break;
                    }

                    // validasi data
                    $class = trim((string)$rowExcelData[4]);
                    $year = trim((string)$rowExcelData[5]);
                    if (in_array($class, $arrClass) && in_array($year, $arrYear)) {
                        $itemArray[] = [
                            'nis' => trim((string)$rowExcelData[0]),
                            'nisn' => trim((string)$rowExcelData[1]),
                            'name' => trim((string)$rowExcelData[2]),
                            'gender' => trim((string)$rowExcelData[3]),
                            'class' => trim((string)$rowExcelData[4]),
                            'year' => trim((string)$rowExcelData[5]),
                        ];
                    } else {
                        $error = 'Data tidak valid pada baris ke ' . $row_number;
                        break;
                    }
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
