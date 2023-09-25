<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\FingerLog;

// use yii\helpers\Json;

class ApiController extends Controller
{

    public function actionFinger() {
        $body = json_encode(file_get_contents('php://input'), true);
        $log = new FingerLog();
        $log->body = $body;
        $log->save();
        return 'OK';
    }

}
