<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Product;
use common\models\UserOutlet;
use common\models\Order;
use common\models\OrderDetail;
use common\models\Setting;
use yii\data\ActiveDataProvider;
use DateTime;
use DatePeriod;
use DateInterval;
use yii\db\Query;

class ApiOutletHomeController extends Controller
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

    public function actionSummary($startdate = null, $enddate = null)
    {
        // init variable
        $arrSummary = [];

        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [[
                'message' => 'Outlet not found'
            ]];
        }
        $outlet_id = $userOutlet->outlet_id;

        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');

        // pending order count
        $pendingOrder = Order::find()
            ->where(['outlet_id' => $outlet_id])
            ->andWhere(['>=', 'ordered_at', $startdate])
            ->andWhere(['<=', 'ordered_at', $enddate])
            ->andWhere(['status' => 'ORDER'])
            ->count();

        // total PAID order
        $totalPaid = 0;
        $orders = Order::find()
            ->where(['outlet_id' => $outlet_id])
            ->andWhere(['>=', 'ordered_at', $startdate])
            ->andWhere(['<=', 'ordered_at', $enddate])
            ->andWhere(['status' => 'PAID'])
            ->all();
        foreach ($orders as $order) {
            $totalPaid += $order->total;
        }

        $arrSummary = [
            'pending_order' => $pendingOrder,
            'total_paid' => Strings::getRupiah($totalPaid),
        ];

        return $arrSummary;
    }

}
