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

class ApiOutletWebviewController extends Controller
{

    public function actionInvoice($number)
    {
        $order = Order::find()->where(['invoice_number' => $number])->one();
        if (!$order) {
            return 'Invoice not found';
        }
        return $this->renderPartial('invoice', [
            'order' => $order,
        ]);
    }

    public function actionDownloadInvoice($number)
    {
        $order = Order::find()->where(['invoice_number' => $number])->one();
        if (!$order) {
            return 'Invoice not found';
        }
        $content = $this->renderPartial('invoice', [
            'order' => $order,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'INVOICE-'.$number.'.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'marginTop' => '10',
            'marginLeft' => '20',
            'content' => $content,
            // 'cssFile' => '@frontend/web/css/invoice.css',
            // 'cssFile' => Yii::getAlias('@fronturl').'/css/invoice.css',
            'options' => [
                'showWatermarkText' => true,
                'showWatermarkImage' => true,
            ],
            'methods' => [
                'SetTitle' => 'INVOICE-'.$number,
            ]
        ]);
        // return $content;

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        $headers->add('Content-Disposition', 'attachment');
        $headers->add('filename', 'INVOICE-'.$number.'.pdf');
        return $pdf->render();
    }

    public function actionChart($user_id)
    {
        // variable definition
        $jsonMonth = '';
        $jsonTotal = '';

        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => $user_id])->one();
        if (!$userOutlet) {
            return 'User outlet not found';
        }
        $outlet_id = $userOutlet->outlet_id;

        // loop last 6 month
        $dateTime = new DateTime('first day of this month');
        for ($i = 1; $i <= 5; $i++) {
            $dateTime->modify('-1 month');
        }
        for ($i = 1; $i <= 6; $i++) {
            $orders = Order::find()
                ->where(['outlet_id' => $outlet_id])
                ->andWhere(['year(ordered_at)' => $dateTime->format('Y')])
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
