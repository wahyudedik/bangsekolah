<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Supplier;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiSupplierController extends Controller
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

    public function actionSupplier($id = null)
    {
        if ($id) {
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new \yii\web\NotFoundHttpException('Data tidak ditemukan');
            return $supplier;
        } else {
            $query = Supplier::find()->all();
            return $query;
        }
    }

}
