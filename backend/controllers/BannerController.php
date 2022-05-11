<?php

namespace backend\controllers;

use Yii;
use common\models\Banner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('banner', 'index');

        $query = Banner::find()->orderBy(['position' => SORT_ASC]);
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
     * Displays a single Banner model.
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
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();

        // reset position
        $banners = Banner::find()->orderBy(['position' => SORT_ASC])->all();
        $number = 1;
        foreach ($banners as $banner) {
            $banner->position = $number++;
            $banner->save();
        }

        // get last number by banner count
        $lastBanner = Banner::find()->orderBy(['position' => SORT_DESC])->limit(1)->one();
        if ($lastBanner) {
            $model->position = ($lastBanner->position + 1);
        } else {
            $model->position = 1;
        }

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/banner/'.$temporary_image);
                $image->saveAs($path);
                Image::thumbnail($path, 1920, 500)->save(Yii::getAlias('@uploads/banner/large-'.$temporary_image), ['quality' => 100]);
                Image::thumbnail($path, 200, 200)->save(Yii::getAlias('@uploads/banner/').'small-'.$temporary_image, ['quality' => 100]);
                $model->image = $temporary_image;
                @unlink($path);
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
     * Updates an existing Banner model.
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
                $path = Yii::getAlias('@uploads/banner/'.$temporary_image);
                $image->saveAs($path);
                Image::thumbnail($path, 1920, 500)->save(Yii::getAlias('@uploads/banner/large-'.$temporary_image), ['quality' => 100]);
                Image::thumbnail($path, 200, 200)->save(Yii::getAlias('@uploads/banner/').'small-'.$temporary_image, ['quality' => 100]);
                $model->image = $temporary_image;
                @unlink($path);

                @unlink(Yii::getAlias('@uploads/banner/small-'.$oldImage));
                @unlink(Yii::getAlias('@uploads/banner/large-'.$oldImage));
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
     * Deletes an existing Banner model.
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
        @unlink(Yii::getAlias('@uploads/banner/small-'.$oldImage));
        @unlink(Yii::getAlias('@uploads/banner/large-'.$oldImage));

        // reset position
        $banners = Banner::find()->orderBy(['position' => SORT_ASC])->all();
        $number = 1;
        foreach ($banners as $banner) {
            $banner->position = $number++;
            $banner->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUp($id)
    {
        $banner = Banner::findOne($id);
        $userBanners = Banner::find()->orderBy(['position' => SORT_ASC])->all();
        foreach ($userBanners as $rm) {
            if (($rm->position + 1) == $banner->position) {
                $rm->position = $rm->position + 1;
                $rm->save();
            }
        }
        $banner->position = $banner->position - 1;
        $banner->save();

        // return grid menu
        $query = Banner::find()->orderBy(['position' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100]
        ]);
        return $this->renderPartial('_grid_banner', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDown($id)
    {
        $banner = Banner::findOne($id);
        $userBanners = Banner::find()->orderBy(['position' => SORT_ASC])->all();
        foreach ($userBanners as $rm) {
            if (($rm->position - 1) == $banner->position) {
                $rm->position = $rm->position - 1;
                $rm->save();
            }
        }
        $banner->position = $banner->position + 1;
        $banner->save();
        
        // return grid menu
        $query = Banner::find()->orderBy(['position' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100]
        ]);
        return $this->renderPartial('_grid_banner', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
