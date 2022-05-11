<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Navbar;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NavbarController implements the CRUD actions for Navbar model.
 */
class NavbarController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'up', 'down'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)  {
                            return User::can();
                        }
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'delete' => ['POST'],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all Navbar models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('navbar', 'index');

        $query = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_ASC]);
        if ($q !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'field_name', $q],
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
        ]);
    }

    /**
     * Displays a single Navbar model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Navbar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Navbar();
        $model->status = 1;

        // reset position
        $navbars = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_ASC])->all();
        $number = 1;
        foreach ($navbars as $navbar) {
            $navbar->position = $number++;
            $navbar->save();
        }

        // get last number by banner count
        $lastNavbar = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_DESC])->limit(1)->one();
        if ($lastNavbar) {
            $model->position = ($lastNavbar->position + 1);
        } else {
            $model->position = 1;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_id == null) {
                $model->level = 1;
            } else {
                $model->level = $model->parent->level + 1;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Navbar berhasil disimpan');
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['create']);
            // return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Navbar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_id == null) {
                $model->level = 1;
            } else {
                $model->level = $model->parent->level + 1;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Navbar berhasil diupdate');
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Navbar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // reset position
        $navbars = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_ASC])->all();
        $number = 1;
        foreach ($navbars as $navbar) {
            $navbar->position = $number++;
            $navbar->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Navbar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Navbar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Navbar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUp($id)
    {
        $navbar = Navbar::findOne($id);
        $navbars = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_ASC])->all();
        $reset = 1;
        foreach ($navbars as $nav) {
            if (($nav->position + 1) == $navbar->position) {
                $nav->position = $nav->position + 1;
                $nav->save();
            }
        }
        $navbar->position = $navbar->position - 1;
        $navbar->save();
        return $this->redirect(['index']);
    }

    public function actionDown($id)
    {
        $navbar = Navbar::findOne($id);
        $navbars = Navbar::find()->where(['level' => 1])->orderBy(['position' => SORT_ASC])->all();
        $reset = 1;
        foreach ($navbars as $nav) {
            if (($nav->position - 1) == $navbar->position) {
                $nav->position = $nav->position - 1;
                $nav->save();
            }
        }
        $navbar->position = $navbar->position + 1;
        $navbar->save();
        return $this->redirect(['index']);
    }


}
