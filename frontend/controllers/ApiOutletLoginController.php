<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\models\User;
use common\models\Recipe;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiOutletLoginController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $user = User::find()->where(['username' => $username, 'role_id' => 4])->one();
        if (!$user) throw new \yii\web\NotFoundHttpException('User tidak ditemukan');
        if (Yii::$app->security->validatePassword($password, $user->password_hash)) {

            // cek apakah status user aktif
            if ($user->status !== 'ACTIVE') {
                throw new \yii\web\NotFoundHttpException('Akun Anda telah dinonaktifkan');
            }

            $result = [
                'auth_key' => $user->auth_key,
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'name' => $user->name,
                'username' => $user->username,
                'password' => $password,
                'role' => $user->role->name,
            ];
            return $result;
        } else {
            throw new \yii\web\UnauthorizedHttpException('Username atau password Anda salah');
        }
    }

}
