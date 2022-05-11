<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Recipe;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiUserController extends Controller
{

    public function beforeAction($action)
    {
        // disable csrf and set response format JSON
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // check BasicAuth
        $user = BasicAuth::user();
        if ($user == null) throw new \yii\web\UnauthorizedHttpException('Unauthorized access');
        
        // if everything is going well
        return parent::beforeAction($action);
    }

    public function actionDetail($id)
    {
        $user = User::findOne($id);
        if (!$user) throw new \yii\web\NotFoundHttpException('Data tidak ditemukan');
        return [
            'id' => $user->id.'',
            'name' => $user->name.'',
            'username' => $user->username.'',
            'email' => $user->email.'',
            'password' => $user->password_hash.'',
            'phone' => $user->phone.'',
            'role' => $user->role->name.'',
        ];
    }

    public function actionUpdate($id)
    {

        $user = User::findOne($id);
        if (!$user) throw new \yii\web\NotFoundHttpException('Data tidak ditemukan');
        
        $user->name = Yii::$app->request->post('name');
        $user->email = Yii::$app->request->post('email');
        $user->username = Yii::$app->request->post('username');
        $user->phone = Yii::$app->request->post('phone');
        if (Yii::$app->request->post('password')) {
            $user->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('password'));
        }

        if ($user->save()) {

            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
            ];
        } else {
            foreach ($user->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

}
