<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex($title = null, $author = null, $status = 'all')
    {
        \common\helpers\Sidebar::setActiveMenu('post', 'index');

        // if writer, only allow view his posts
        $user = Yii::$app->user->identity;
        if ($user->role_id == 2) {
            $query = Post::find()->where(['created_by' => $user->id])->orderBy(['published_at' => SORT_DESC]);
        } else {
            $query = Post::find()->orderBy(['published_at' => SORT_DESC]);
        }

        if ($title !== null) {
            $query->andFilterWhere(['and', ['like', 'title', $title]]);
        }
        if ($author !== null) {
            $writers = User::find()->where(['like', 'name', $author])->all();
            $idWriters = [0];
            foreach ($writers as $writer) {
                $idWriters[] = $writer->id;
            }
            $query->andFilterWhere(['and', ['in', 'created_by', $idWriters]]);
        }
        if ($status !== 'all') {
            if ($status == 'published') {
                $query->andFilterWhere(['and', ['status' => 1]]);
            } else if ($status == 'scheduled') {
                $query->andFilterWhere(['and', ['status' => 0]]);
                $query->andFilterWhere(['>', 'scheduled_at', date('Y-m-d H:i:s')]);
            } else if ($status == 'draft') {
                $query->andFilterWhere(['and', ['status' => 0]]);
            }
        }

        // return $query->createCommand()->getRawSql();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title,
            'author' => $author,
            'status' => $status,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->status = 0;
        $model->read = 0;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $explodedName = explode('.', $image->name);
                $extension = end($explodedName);
                $temporaryName = Yii::$app->security->generateRandomString() . ".{$extension}";
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'JPG', 'JPEG', 'PNG'])) {
                    $path = Yii::getAlias('@uploads/posts/'.$temporaryName);
                    $image->saveAs($path);
                    Image::thumbnail($path, 1240, 892)->save(Yii::getAlias('@uploads/posts/large-'.$temporaryName), ['quality' => 100]);
                    Image::thumbnail($path, 620, 446)->save(Yii::getAlias('@uploads/posts/small-'.$temporaryName), ['quality' => 80]);
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

                // send Whatsapp notification
                // $message = 'THESMEDIA: '.$model->createdBy->name.' created new post "'.$model->title.'". Check this out: '.Yii::getAlias('@fronturl/posts/'.$model->slug);
                // $ch = curl_init(); 
                // curl_setopt($ch, CURLOPT_URL, 'https://api.callmebot.com/whatsapp.php?phone=+6285736775369&text='.urlencode($message).'&apikey=871039');
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                // $output = curl_exec($ch); 
                // curl_close($ch);      

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
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
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'JPG', 'JPEG', 'PNG'])) {
                    $path = Yii::getAlias('@uploads/posts/'.$temporaryName);
                    $image->saveAs($path);
                    Image::thumbnail($path, 1240, 892)->save(Yii::getAlias('@uploads/posts/large-'.$temporaryName), ['quality' => 100]);
                    Image::thumbnail($path, 620, 446)->save(Yii::getAlias('@uploads/posts/small-'.$temporaryName), ['quality' => 80]);
                    @unlink($path);
                    @unlink(Yii::getAlias('@uploads/posts/small-'.$oldImage));
                    @unlink(Yii::getAlias('@uploads/posts/large-'.$oldImage));
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
                    $path = Yii::getAlias('@uploads/posts/'.$temporaryName);
                    $video->saveAs($path);
                    $model->video = $temporaryName;
                    @unlink(Yii::getAlias('@uploads/posts/'.$oldVideo));
                }
            } else {
                $model->video = $oldVideo;
            }

            $model->tag = json_encode($model->tags);
            if ($model->status == 1 && $model->published_at == null) {
                $model->published_at = date('Y-m-d H:i:s');
            }
            if ($model->save()) {

                // send Whatsapp notification
                $message = 'THESMEDIA: '.$model->createdBy->name.' just edited "'.$model->title.'". Check this out: '.Yii::getAlias('@fronturl/posts/'.$model->slug);
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, 'https://api.callmebot.com/whatsapp.php?phone=+6285736775369&text='.urlencode($message).'&apikey=871039');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                $output = curl_exec($ch); 
                curl_close($ch);      

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
     * Deletes an existing Post model.
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

        @unlink(Yii::getAlias('@uploads/posts/small-'.$oldImage));
        @unlink(Yii::getAlias('@uploads/posts/large-'.$oldImage));
        @unlink(Yii::getAlias('@uploads/posts/'.$oldVideo));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
