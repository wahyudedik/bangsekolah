<?php

namespace frontend\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\models\Order;
use common\models\UserOutlet;
use DateTime;

class ApiWebviewController extends Controller
{

    public function actionChart()
    {
        // variable definition
        $jsonMonth = '';
        $jsonTotal = '';

        // loop last 6 month
        $dateTime = new DateTime('first day of this month');
        for ($i = 1; $i <= 5; $i++) {
            $dateTime->modify('-1 month');
        }
        for ($i = 1; $i <= 6; $i++) {
            $orders = Order::find()
                ->where(['year(ordered_at)' => $dateTime->format('Y')])
                ->andWhere(['month(ordered_at)' => $dateTime->format('m')])
                ->andWhere(['in', 'status', ['FINALIZE', 'SENT', 'PAID']])
                ->all();
            $totalInMonth = 0;
            foreach ($orders as $order) {
                $totalInMonth += $order->total;
            }

            // add value to array
            $jsonMonth .= '"'.$dateTime->format('M Y').'", ';
            $jsonTotal .= $totalInMonth.', ';

            // modify date -1 month
            $dateTime->modify('+1 month');
        }

        return $this->renderPartial('chart', [
            'jsonMonth' => $jsonMonth,
            'jsonTotal' => $jsonTotal,
        ]);
    }
}
