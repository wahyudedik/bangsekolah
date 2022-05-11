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
use common\models\OrderDetailItem;
use common\models\Setting;
use common\models\Item;
use yii\data\ActiveDataProvider;
use DateTime;
use DatePeriod;
use DateInterval;
use yii\db\Query;

class ApiOutletOrderController extends Controller
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

    public function actionIndex($number = null, $status = null)
    {
        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [[
                'message' => 'Outlet not found'
            ]];
        }
        $outlet_id = $userOutlet->outlet_id;

        if ($status) {
            // get order with status filter
            $orders = Order::find()
                ->where(['outlet_id' => $outlet_id])
                ->andWhere(['status' => $status])
                ->orderBy(['id' => SORT_DESC])
                ->all();
        } else {
            // get order with non CART status
            $orders = Order::find()
                ->where(['outlet_id' => $outlet_id])
                ->andWhere(['!=', 'status', 'CART'])
                ->orderBy(['id' => SORT_DESC])
                ->all();
            }
        if (count($orders) == 0) {
            return [[
                'message' => 'You have no orders'
            ]];
        }

        // loop order
        $arrOrder = [];
        foreach ($orders as $order) {
            if ($number) {
                if (stripos($order->order_number, $number) !== FALSE) {
                    $arrOrder[] = [
                        'id' => $order->id.'',
                        'order_number' => $order->order_number,
                        'invoice_number' => $order->invoice_number,
                        'outlet_id' => $order->outlet_id.'',
                        'status' => $order->status,
                        'created_at' => date('d M Y', strtotime($order->created_at)),
                    ];
                }
            } else {
                $arrOrder[] = [
                    'id' => $order->id.'',
                    'order_number' => $order->order_number,
                    'invoice_number' => $order->invoice_number,
                    'outlet_id' => $order->outlet_id.'',
                    'status' => $order->status,
                    'created_at' => date('d M Y', strtotime($order->created_at)),
                ];
            }
        }

        return $arrOrder;
    }

    public function actionView($id)
    {
        $order = Order::findOne($id);
        if (!$order) {
            return [
                'message' => 'Order is not found'
            ];
        }

        return [
            'id' => $order->id.'',
            'order_number' => $order->order_number,
            'invoice_number' => $order->invoice_number.'',
            'outlet_id' => $order->outlet_id.'',
            'outlet_name' => $order->outlet->name,
            'note' => $order->note.'',
            'seller_note' => $order->seller_note.'',
            'shipping_cost' => $order->shipping_cost ? $order->shipping_cost : 0,
            'shipping_cost_rupiah' => Strings::getRupiah($order->shipping_cost),
            'shipping_courier' => $order->shipping_courier.'',
            'tax' => $order->tax ? $order->tax : 0,
            'tax_rupiah' => Strings::getRupiah($order->tax),
            'tax_description' => $order->tax_description.'',
            'status' => $order->status,
            'created_at' => date('d M Y', strtotime($order->created_at)),
            'ordered_at' => $order->ordered_at ? date('d M Y', strtotime($order->ordered_at)) : '',
            'finalized_at' => $order->finalized_at ? date('d M Y', strtotime($order->finalized_at)) : '',
            'paid_at' => $order->paid_at ? date('d M Y', strtotime($order->paid_at)) : '',
            'created_by' => $order->createdBy->name,
            'finalized_by' => $order->finalizedBy ? $order->finalizedBy->name : '',
        ];
    }

    public function actionOrderDetail($id)
    {
        $details = OrderDetail::find()->where(['order_id' => $id])->all();

        $arrDetail = [];
        foreach ($details as $detail) {
            $arrDetail[] = [
                'id' => $detail->id.'',
                'order_id' => $detail->order_id.'',
                'product_id' => $detail->product_id.'',
                'product_name' => $detail->product->name,
                'price_sale' => $detail->price_sale,
                'price_sale_rupiah' => Strings::getRupiah($detail->price_sale),
                'quantity' => $detail->quantity,
                'quantity_unit' => $detail->quantity.' '.$detail->product->unit,
                'status' => $detail->status,
                'created_at' => date('d M Y', strtotime($detail->created_at)),
                'created_by' => $detail->createdBy->name,
            ];
        }

        return $arrDetail;
    }

    public function actionOrderDetailItem($order_detail_id)
    {
        $orderDetailItems = OrderDetailItem::find()
            ->where(['order_detail_id' => $order_detail_id])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($orderDetailItems) > 0) {
            foreach ($orderDetailItems as $orderDetailItem) {
                $arrOrderDetailItem[] = [
                    'id' => $orderDetailItem->id.'',
                    'product_name' => $orderDetailItem->orderDetail->product->name,
                    'item_code' => $orderDetailItem->item->code,
                    'exp_date' => date('d M Y', strtotime($orderDetailItem->item->expired_date)),
                    'status' => $orderDetailItem->status,
                ];
            }
        } else {
            $arrOrderDetailItem[] = [
                'message' => 'Item not found'
            ];
        }
        return $arrOrderDetailItem;
    }

    public function actionItemVerify()
    {
        // get post data
        $item_code = Yii::$app->request->post('item_code');

        // find item
        $item = Item::find()->where(['code' => $item_code])->one();
        if (!$item) {
            return [
                'message' => 'Item code '.$item_code.' is not found'
            ];
        }

        // find order_detail_item
        $orderDetailItem = OrderDetailItem::find()->where(['item_id' => $item->id])->one();
        if (!$orderDetailItem) {
            return [
                'message' => 'Order detail item is not found'
            ];
        }

        // update order_detail_item to VERIFIED
        $orderDetailItem->status = 'VERIFIED';
        if ($orderDetailItem->save()) {

            // check if all order_detail products is verified
            $orderDetail = $orderDetailItem->orderDetail;
            $productItemCount = $orderDetail->quantity;
            $verifiedItemCount = 0;
            foreach ($orderDetail->orderDetailItems as $orderDetailItem) {
                if ($orderDetailItem->status == 'VERIFIED') {
                    $verifiedItemCount++;
                }
            }
            if ($productItemCount == $verifiedItemCount) {
                $orderDetail->status = 'VERIFIED';
                if(!$orderDetail->save()) {
                    foreach ($orderDetail->errors as $error) {
                        return [
                            'message' => $error[0]
                        ];
                    }
                }
            }

            return [
                'message' => 'Product item '.$item_code.' verified'
            ];
        } else {
            foreach ($orderDetailItem->errors as $error) {
                return [
                    'message' => $error[0]
                ];
            }
        }
    }

    public function actionUpdateNote()
    {
        // get post data
        $order_id = Yii::$app->request->post('order_id');
        $note = Yii::$app->request->post('note');
        if (!$order_id && !$note) {
            return [
                'message' => 'Parameter is missing'
            ];
        }

        // get orderDetail
        $order = Order::findOne($order_id);
        if (!$order) {
            return [
                'message' => 'Order is not found'
            ];
        }

        // update note
        $order->note = $note;
        if ($order->save()) {
            return [
                'message' => 'Order '.$order->order_number. ' note successfully updated'
            ];
        } else {
            return [
                'message' => 'Order note failed to update'
            ];
        }
    }
}
