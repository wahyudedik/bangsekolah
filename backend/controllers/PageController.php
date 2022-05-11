<?php

namespace backend\controllers;

use Yii;
use common\models\Page;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'delete' => ['POST'],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('page', 'index');

        $query = Page::find()->orderBy(['id' => SORT_DESC]);
        if ($q !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'title', $q],
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
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        $model->status = 0;
        $model->read = 0;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $explodedName = explode('.', $image->name);
                $extension = end($explodedName);
                $temporaryName = Yii::$app->security->generateRandomString() . ".{$extension}";
                if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'jfif') {
                    $path = Yii::getAlias('@uploads/pages/'.$temporaryName);
                    $image->saveAs($path);
                    Image::thumbnail($path, 1240, 892)->save(Yii::getAlias('@uploads/pages/large-'.$temporaryName), ['quality' => 100]);
                    Image::thumbnail($path, 620, 446)->save(Yii::getAlias('@uploads/pages/small-'.$temporaryName), ['quality' => 100]);
                    @unlink($path);
                } else {
                    $temporaryName = $temporaryName;
                }
                $model->image = $temporaryName;
            }

            $model->tag = json_encode($model->tags);
            $model->created_by = Yii::$app->user->identity->id;
            if ($model->status == 1) {
                $model->published_at = date('Y-m-d H:i:s');
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->tags = json_decode($model->tag);
        $oldImage = $model->image;
        $oldVideo = $model->video;

        if ($model->load(Yii::$app->request->post())) {

            // handle uploaded image
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $explodedName = explode('.', $image->name);
                $extension = end($explodedName);
                $temporaryName = Yii::$app->security->generateRandomString() . ".{$extension}";
                if ($extension == 'jpg' || $extension == 'png' || $extension == 'webp' || $extension == 'jfif') {
                    $path = Yii::getAlias('@uploads/pages/'.$temporaryName);
                    $image->saveAs($path);
                    Image::thumbnail($path, 1240, 892)->save(Yii::getAlias('@uploads/pages/large-'.$temporaryName), ['quality' => 100]);
                    Image::thumbnail($path, 620, 446)->save(Yii::getAlias('@uploads/pages/small-'.$temporaryName), ['quality' => 100]);
                    @unlink($path);
                    @unlink(Yii::getAlias('@uploads/pages/small-'.$oldImage));
                    @unlink(Yii::getAlias('@uploads/pages/large-'.$oldImage));
                    $model->image = $temporaryName;
                }
            } else {
                $model->image = $oldImage;
            }

            // handle uploaded video
            $video = UploadedFile::getInstance($model, 'video');
            if (isset($video)) {
                $explodedName = explode('.', $video->name);
                $extension = end($explodedName);
                $temporaryName = Yii::$app->security->generateRandomString() . ".{$extension}";
                if ($extension == 'mp4') {
                    $path = Yii::getAlias('@uploads/pages/'.$temporaryName);
                    $video->saveAs($path);
                    $model->video = $temporaryName;
                    @unlink(Yii::getAlias('@uploads/pages/'.$oldVideo));
                }
            } else {
                $model->video = $oldVideo;
            }

            $model->tag = json_encode($model->tags);
            if ($model->status == 1 && $model->published_at == null) {
                $model->published_at = date('Y-m-d H:i:s');
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->image;
        $oldVideo = $model->video;

        $model->delete();

        @unlink(Yii::getAlias('@uploads/pages/small-'.$oldImage));
        @unlink(Yii::getAlias('@uploads/pages/large-'.$oldImage));
        @unlink(Yii::getAlias('@uploads/pages/'.$oldVideo));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
