<?php

namespace backend\controllers;

use Yii;
use common\models\RecipeDetail;
use common\models\Recipe;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecipeDetailController implements the CRUD actions for RecipeDetail model.
 */
class RecipeDetailController extends Controller
{
   

    /**
     * Lists all RecipeDetail models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $recipe = Recipe::find()->where(['id' => $id])->one();
        $dataProvider = new ActiveDataProvider([
            'query' => RecipeDetail::find()->where(['recipe_id' => $id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'recipe' => $recipe,
            'id' => $id,
        ]);
    }

    /**
     * Displays a single RecipeDetail model.
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
     * Creates a new RecipeDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $recipe = Recipe::find()->where(['id' => $id])->one();

        $model = new RecipeDetail();
        $model->recipe_id = $recipe->id;
        $model->created_by = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Raw material saved successfully');
                return $this->redirect(['create', 'id' => $recipe->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['create', 'id' => $recipe->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'recipe' => $recipe,
            ]);
        }

        
    }

    /**
     * Updates an existing RecipeDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Ingredients berhasil diupdate');
            return $this->redirect(['index', 'id' => $model->recipe_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RecipeDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $recipe_id = $model->recipe_id;
        $model->delete();

        return $this->redirect(['index', 'id' => $recipe_id]);
    }

    /**
     * Finds the RecipeDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecipeDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecipeDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
