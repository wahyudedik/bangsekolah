<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSchool;
use common\models\School;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)  {
                            return User::can();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('user', 'index');

        $query = User::find()->where(['status' => 'ACTIVE'])->orderBy(['id' => SORT_DESC]);
        if ($q !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'name', $q],
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
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = null)
    {
        if ($id == null) {
            $id = Yii::$app->user->identity->id;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChangePassword($id = null)
    {
        if ($id == null) {
            $id = Yii::$app->user->identity->id;            
        }
        $model = $this->findModel($id);
        $model->mode_change_password = true;
        if ($model->load(Yii::$app->request->post())) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Password changed successfully');
            } else {
                Yii::$app->session->setFlash('danger', 'Password failed to change');
            }
            if ($id) {
                return $this->redirect(['view', 'id' => $id]);
            } else {
                return $this->redirect(['view']);
            }
        } else {
            return $this->render('change-password', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($school_id = null)
    {
        $model = new User();
        $model->mode_create = true;
        $image = '';

        if ($model->load(Yii::$app->request->post())) {

            // cek jika username sudah terdaftar maka ditolak
            $user = User::find()->where(['username' => $model->username])->one();
            if ($user) {
                Yii::$app->session->setFlash('danger', 'Username already registered');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/user/'.$temporary_image);
                $image->saveAs($path);

                // memastikan thumbnail besar memiliki rasio 1:1 (square) dan maksimal 800x800
                // jika ukuran kurang dari 800x800, maka dipilih ukuran terkecil agar rasionya tetap square
                $imageSize = getimagesize($path);
                if ($imageSize[0] >= 800 && $imageSize[1] >= 800) {
                    Image::thumbnail($path, 800, 800)->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                } else if ($imageSize[0] <= 800 || $imageSize[1] <= 800) {
                    if ($imageSize[0] < $imageSize[1]) {
                        Image::thumbnail($path, $imageSize[0], $imageSize[0])->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                    } else {
                        Image::thumbnail($path, $imageSize[1], $imageSize[1])->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                    }
                }
                Image::thumbnail($path, 100, 100)->save(Yii::getAlias('@uploads/user/').'small-'.$temporary_image, ['quality' => 100]);

                $image = 'large-'.$temporary_image;

                @unlink($path);
            }

            /**
             * simpan image dan password
             */
            $model->image = $image;
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            if($school_id) {
                $model->role_id = 2;
            }

            if ($model->save()) {
                if($school_id) {
                    $user_school = new UserSchool;
                    $user_school->user_id = $model->id;
                    $user_school->school_id = $school_id;
                    $user_school->save();
                }
                Yii::$app->session->setFlash('success', 'User saved successfully');
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $school = School::findOne($school_id);
            return $this->render('create', [
                'model' => $model,
                'school_id' => $school_id,
                'school' => $school,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($school_id = null, $id = null)
    {
        //$id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $old_image = $model->image;

        if ($model->load(Yii::$app->request->post())) {

            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/user/'.$temporary_image);
                $image->saveAs($path);

                // memastikan thumbnail besar memiliki rasio 1:1 (square) dan maksimal 800x800
                // jika ukuran kurang dari 800x800, maka dipilih ukuran terkecil agar rasionya tetap square
                $imageSize = getimagesize($path);
                if ($imageSize[0] >= 800 && $imageSize[1] >= 800) {
                    Image::thumbnail($path, 800, 800)->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                } else if ($imageSize[0] <= 800 || $imageSize[1] <= 800) {
                    if ($imageSize[0] < $imageSize[1]) {
                        Image::thumbnail($path, $imageSize[0], $imageSize[0])->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                    } else {
                        Image::thumbnail($path, $imageSize[1], $imageSize[1])->save(Yii::getAlias('@uploads/user/large-'.$temporary_image), ['quality' => 100]);
                    }
                }
                Image::thumbnail($path, 100, 100)->save(Yii::getAlias('@uploads/user/').'small-'.$temporary_image, ['quality' => 100]);

                $model->image = 'large-'.$temporary_image;

                @unlink($path);
                @unlink(Yii::getAlias('@uploads/user/'.$old_image));
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'User saved successfully');
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'school_id' => $school_id,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $query = $this->findModel($id);
        $query->status = 'NONACTIVE';
        if($query->save()){
            return $this->redirect(['index']);
        }

        
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
