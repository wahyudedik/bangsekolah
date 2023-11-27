<?php

namespace backend\controllers;

use Yii;
use common\models\Day;
use common\models\SchoolClass;
use common\models\LessonScheduleTemplate;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LessonScheduleTemplateController implements the CRUD actions for LessonScheduleTemplate model.
 */
class LessonScheduleTemplateController extends Controller
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
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LessonScheduleTemplate models.
     * @return mixed
     */
    public function actionIndex($class = null)
    {
        \common\helpers\Sidebar::setActiveMenu('lesson-schedule-template', 'index');

        $school_id = Yii::$app->session->get('school_id');

        // get first class
        if ($class) {
            $schoolClass = SchoolClass::find()->where(['school_id' => $school_id, 'name' => $class])->one();
        } else {
            $schoolClass = SchoolClass::find()->where(['school_id' => $school_id])->one();
            $class = $schoolClass->name;
        }

        $days = Day::find()->where(['school_id' => $school_id])->all();
        $model = new LessonScheduleTemplate();
        $model->class = $schoolClass->name;

        return $this->render('index', [
            'class' => $class,
            'days' => $days,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single LessonScheduleTemplate model.
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
     * Creates a new LessonScheduleTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LessonScheduleTemplate();
        $model->school_id = Yii::$app->session->get('school_id');
        $model->status = 'ACTIVE';

        // $classes = SchoolClass::find()->where(['school_id' => Yii::$app->session->get('school_id')])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            // 'classes' => $classes,
        ]);
    }

    /**
     * Updates an existing LessonScheduleTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LessonScheduleTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->status = 'NONACTIVE';
            $model->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the LessonScheduleTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LessonScheduleTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LessonScheduleTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
