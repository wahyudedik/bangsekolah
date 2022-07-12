<?php

namespace backend\controllers;

use Yii;
use common\models\Student;
use common\models\School;
use common\models\UserSchool;
use common\models\User;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;


class ProfileController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->identity->role_id > 1) {
            $model = UserSchool::find()->where(['school_id' => Yii::$app->session->get('school_id')])->one();
            return $this->render('index-user-school', [
                'model' => $model,
            ]);
        } else {
            $model = User::findOne(Yii::$app->user->identity->id);
            return $this->render('index-super-admin', [
                'model' => $model,
            ]);
        }

    }

    public function actionUpdate($id_user = null)
    {
        $model = User::findOne($id_user);
        $school = School::findOne(Yii::$app->session->get('school_id'));
        $old_image = $school->image == null ? '' : $school->image;

        if ($model->load(Yii::$app->request->post())) {

            $image = UploadedFile::getInstance($model, 'logo');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/school/'.$temporary_image);
                $image->saveAs($path);

                // memastikan thumbnail besar memiliki rasio 1:1 (square) dan maksimal 800x800
                // jika ukuran kurang dari 800x800, maka dipilih ukuran terkecil agar rasionya tetap square
                $imageSize = getimagesize($path);
                if ($imageSize[0] >= 800 && $imageSize[1] >= 800) {
                    Image::thumbnail($path, 800, 800)->save(Yii::getAlias('@uploads/school/large-'.$temporary_image), ['quality' => 100]);
                } else if ($imageSize[0] <= 800 || $imageSize[1] <= 800) {
                    if ($imageSize[0] < $imageSize[1]) {
                        Image::thumbnail($path, $imageSize[0], $imageSize[0])->save(Yii::getAlias('@uploads/school/large-'.$temporary_image), ['quality' => 100]);
                    } else {
                        Image::thumbnail($path, $imageSize[1], $imageSize[1])->save(Yii::getAlias('@uploads/school/large-'.$temporary_image), ['quality' => 100]);
                    }
                }

                $school->image = 'large-'.$temporary_image;

                @unlink($path);
                if (!$old_image == '') {
                    @unlink(Yii::getAlias('@uploads/school/'.$old_image));
                }
                
            }

            if ($model->save()) {
                if(!$school->save()){
                    foreach ($school->getErrors() as $error) { $message = $error[0]; }
                    Yii::$app->session->setFlash('danger', $message);
                }

                Yii::$app->session->setFlash('success', 'Berhasil diperbarui'. $school->image);
            } else {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['index']);

            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword($id = null)
    {
        if ($id == null) {
            $id = Yii::$app->user->identity->id;            
        }
        
        $model = User::findOne($id);
        $model->mode_change_password = true;
        if ($model->load(Yii::$app->request->post())) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Password changed successfully');
            } else {
                Yii::$app->session->setFlash('danger', 'Password failed to change');
            }

            return $this->redirect(['view']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }


}
