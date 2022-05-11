<?php

namespace backend\controllers;

use Yii;
use common\models\Media;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
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
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('media', 'index');

        $query = Media::find();
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
     * Displays a single Media model.
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
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Media();

        // reset position
        // $medias = Media::find()->orderBy(['position' => SORT_ASC])->all();
        // $number = 1;
        // foreach ($medias as $media) {
        //     $media->position = $number++;
        //     $media->save();
        // }

        // get last number by media count
        // $lastMedia = Media::find()->orderBy(['position' => SORT_DESC])->limit(1)->one();
        // if ($lastMedia) {
        //     $model->position = ($lastMedia->position + 1);
        // } else {
        //     $model->position = 1;
        // }

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/media/'.$temporary_image);
                $image->saveAs($path);
                // Image::thumbnail($path, 1920, 500)->save(Yii::getAlias('@uploads/media/large-'.$temporary_image), ['quality' => 100]);
                // Image::thumbnail($path, 200, 200)->save(Yii::getAlias('@uploads/media/').'small-'.$temporary_image, ['quality' => 100]);
                $model->image = $temporary_image;
                $model->url = Yii::getAlias('@fronturl/uploads/media/'.$temporary_image);
                // @unlink($path);
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
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/media/'.$temporary_image);
                $image->saveAs($path);
                // Image::thumbnail($path, 1920, 500)->save(Yii::getAlias('@uploads/media/large-'.$temporary_image), ['quality' => 100]);
                // Image::thumbnail($path, 200, 200)->save(Yii::getAlias('@uploads/media/').'small-'.$temporary_image, ['quality' => 100]);
                $model->image = $temporary_image;
                $model->url = Yii::getAlias('@fronturl/uploads/media/'.$temporary_image);
                // @unlink($path);

                @unlink(Yii::getAlias('@uploads/media/small-'.$oldImage));
                @unlink(Yii::getAlias('@uploads/media/large-'.$oldImage));
            } else {
                $model->image = $oldImage;
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
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->image;
        $model->delete();

        // delete image
        @unlink(Yii::getAlias('@uploads/media/small-'.$oldImage));
        @unlink(Yii::getAlias('@uploads/media/large-'.$oldImage));

        // reset position
        $medias = Media::find()->orderBy(['position' => SORT_ASC])->all();
        $number = 1;
        foreach ($medias as $media) {
            $media->position = $number++;
            $media->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
