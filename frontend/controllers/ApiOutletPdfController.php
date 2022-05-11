<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;

class ApiOutletWebviewController extends Controller
{

    public function actionInvoice($number)
    {
        return $this->renderPartial('invoice');
    }
}
