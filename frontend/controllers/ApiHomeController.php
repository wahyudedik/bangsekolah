<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Item;
use common\models\Product;
use common\models\Production;
use common\models\Order;
use common\models\OrderDetail;
use common\models\OrderDetailItem;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiHomeController extends Controller
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

    public function actionOrder($tampilan = 'All', $name = '', $startdate = null, $enddate = null)
    {
        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');
        // $end = new DateTime($enddate);
        // $begin = new DateTime($startdate);

        $orders = Order::find()
            ->where(['status' => 'ORDER'])
            ->andWhere(['BETWEEN', 'DATE(ordered_at)', $startdate, $enddate])
            ->orderBy(['id' => SORT_DESC])
            ->limit(10)
            ->all();
        
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $arrOrder[] = [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'outlet' => $order->outlet->name,
                    'status' => $order->status,
                    'shipping_cost' => Strings::getRupiah($order->shipping_cost),
                    'total' => Strings::getRupiah($order->total),
                    'ordered_at' => date('d M Y', strtotime($order->ordered_at)),
                ];
            }
        } else {
            $arrOrder[] = [
                'message' => 'Order not found '.$startdate.'-'.$enddate
            ];
        }
        return $arrOrder;
    }

    public function actionSummary($tampilan = 'All', $name = '', $startdate = null, $enddate = null)
    {
        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');
        // $end = new DateTime($enddate);
        // $begin = new DateTime($startdate);

        $recordNewOrder = Order::find()
            ->where(['status' => 'ORDER'])
            ->andWhere(['BETWEEN', 'DATE(ordered_at)', $startdate, $enddate])
            ->all();
        $recordSuccessOrder = Order::find()
            ->where(['in', 'status', ['FINALIZE', 'PAID']])
            ->andWhere(['BETWEEN', 'DATE(finalized_at)', $startdate, $enddate])
            ->all();
        $recordProduction = Production::find()
            ->where(['status' => 'FINISHED'])
            ->andWhere(['BETWEEN', 'DATE(created_at)', $startdate, $enddate])
            ->all();
        $recordTotalOrder = Order::find()
            ->andWhere(['BETWEEN', 'DATE(ordered_at)', $startdate, $enddate])
            ->all();
        $total = 0;
        foreach ($recordTotalOrder as $totalOrder) {
            $total += $totalOrder->total;
        }
        
        $arrRecord[] = [
            'new_order' => count($recordNewOrder).'',
            'success_order' => count($recordSuccessOrder).'',
            'production' => count($recordProduction).'',
            'total_order' => Strings::getRupiah($total),
        ];
        return $arrRecord;
    }
}
