<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Menu;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('menu', 'index');

        $query = Menu::find()->where(['parent_id' => null])->orderBy(['id' => SORT_ASC]);
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
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $model->icon = 'fa fa-tags';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_id == null) {
                $model->level = 1;
            } else {
                $model->level = $model->parent->level + 1;
            }
            $model->actions = json_encode($model->json_action);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Menu berhasil disimpan');
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->json_action = json_decode($model->actions);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent_id == null) {
                $model->level = 1;
            } else {
                $model->level = $model->parent->level + 1;
            }
            $model->actions = json_encode($model->json_action);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Menu berhasil diupdate');
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
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
