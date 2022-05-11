<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Mail;
use common\helpers\Slack;
use common\helpers\Strings;
use common\helpers\BasicAuth;

use common\models\User;
use common\models\Order;
use common\models\Setting;
use common\models\Product;
use common\models\UserOutlet;
use common\models\OrderDetail;
use yii\data\ActiveDataProvider;

use DateTime;
use DatePeriod;
use DateInterval;
use yii\db\Query;

class ApiOutletCartController extends Controller
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

    public function actionIndex($name = null)
    {
        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [[
                'message' => 'Outlet not found'
            ]];
        }
        $outlet_id = $userOutlet->outlet_id;

        // get order with status CART
        $order = Order::find()->where(['outlet_id' => $outlet_id, 'status' => 'CART'])->one();
        if (!$order) {
            return [[
                'message' => 'Your cart is empty'
            ]];
        }

        // loop order_detail
        $arrProduct = [];
        foreach ($order->orderDetails as $detail) {
            if ($name) {
                if (stripos($detail->product->name, $name) !== FALSE) {
                    $arrProduct[] = [
                        'id' => $detail->id.'',
                        'product_id' => $detail->product_id.'',
                        'product_name' => $detail->product->name,
                        'product_price' => Strings::getRupiah($detail->product->price_sale),
                        'quantity' => $detail->quantity,
                        'subtotal' => ($detail->quantity*$detail->product->price_sale),
                    ];
                }
            } else {
                $arrProduct[] = [
                    'id' => $detail->id.'',
                    'product_id' => $detail->product_id.'',
                    'product_name' => $detail->product->name,
                    'product_price' => Strings::getRupiah($detail->product->price_sale),
                    'quantity' => $detail->quantity,
                    'subtotal' => ($detail->quantity*$detail->product->price_sale),
                ];
            }
        }

        return $arrProduct;
    }

    public function actionAdd()
    {
        // get post data
        $quantity = Yii::$app->request->post('quantity');
        $product_id = Yii::$app->request->post('product_id');
        if (!$quantity && !$product_id) {
            return [
                'message' => 'Parameter is missing'
            ];
        }

        // check product
        $product = Product::findOne($product_id);
        if (!$product) {
            return [
                'message' => 'Product not found'
            ];
        }

        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [
                'message' => 'Outlet not found'
            ];
        }
        $outlet_id = $userOutlet->outlet_id;

        // check if order data with status CART is available
        $order = Order::find()->where(['outlet_id' => $outlet_id, 'status' => 'CART'])->one();
        if (!$order) {
            $order = new Order();
            $order->outlet_id = $outlet_id;
            $order->status = 'CART';
            $order->created_by = BasicAuth::user()->id;
            if (!$order->save()) {
                foreach ($order->errors as $error) {
                    return [
                        'message' => $error[0]
                    ];
                }
            }
        }

        // add product to order_detail
        $orderDetail = OrderDetail::find()->where(['order_id' => $order->id, 'product_id' => $product_id])->one();
        if (!$orderDetail) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $product_id;
            $orderDetail->price_sale = $product->price_sale;
            $orderDetail->quantity = $quantity;
            $orderDetail->status = 'PENDING';
            $orderDetail->created_by = BasicAuth::user()->id;
            $orderDetail->save();
        } else {
            $orderDetail->quantity = $quantity;
            $orderDetail->save();
        }

        return $orderDetail;
    }

    public function actionDetail($id)
    {
        $detail = OrderDetail::findOne($id);
        if (!$detail) {
            return [
                'message' => 'Cart detail is not found'
            ];
        }

        $arrProduct = [
            'id' => $detail->id.'',
            'product_id' => $detail->product_id.'',
            'product_name' => $detail->product->name,
            'product_price' => Strings::getRupiah($detail->product->price_sale),
            'quantity' => $detail->quantity.'',
        ];

        return $arrProduct;
    }

    public function actionUpdate()
    {
        // get post data
        $order_detail_id = Yii::$app->request->post('order_detail_id');
        $quantity = Yii::$app->request->post('quantity');
        if (!$quantity && !$order_detail_id) {
            return [
                'message' => 'Parameter is missing'
            ];
        }

        // get orderDetail
        $detail = OrderDetail::findOne($order_detail_id);
        if (!$detail) {
            return [
                'message' => 'Cart detail is not found'
            ];
        }

        // update quantity
        $detail->quantity = $quantity;
        if ($detail->save()) {
            return [
                'message' => $detail->product->name. ' quantity updated to '.$quantity
            ];
        } else {
            return [
                'message' => 'Product quantity failed to update'
            ];
        }
    }

    public function actionUpdateNote()
    {
        // get post data
        $note = Yii::$app->request->post('note');
        if (!$note) {
            return [
                'message' => 'Parameter is missing'
            ];
        }

        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [
                'message' => 'Outlet not found'
            ];
        }
        $outlet_id = $userOutlet->outlet_id;

        // get order with status CART
        $order = Order::find()->where(['outlet_id' => $outlet_id, 'status' => 'CART'])->one();
        if (!$order) {
            return [
                'message' => 'Active order cart is not found'
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

    public function actionViewNote()
    {
        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [
                'message' => 'Outlet not found'
            ];
        }
        $outlet_id = $userOutlet->outlet_id;

        // get order with status CART
        $order = Order::find()->where(['outlet_id' => $outlet_id, 'status' => 'CART'])->one();
        if (!$order) {
            return [
                'note' => ''
            ];
        }
        
        // return note
        return [
            'note' => $order->note.''
        ];
    }

    public function actionDelete()
    {
        // get post data
        $order_detail_id = Yii::$app->request->post('order_detail_id');

        // get orderDetail
        $detail = OrderDetail::findOne($order_detail_id);
        if (!$detail) {
            return [
                'message' => 'Cart detail is not found'
            ];
        }

        // delete order_detail
        $order = $detail->order;
        $productName = $detail->product->name;
        if ($detail->delete()) {
            // check if order is empty
            if (count($order->orderDetails) == 0) {
                $order->delete();
            }

            return [
                'message' => $productName. ' deleted from cart'
            ];
        } else {
            return [
                'message' => 'Product failed to delete'
            ];
        }
    }

    public function actionCheckout()
    {
        // get outlet_id
        $userOutlet = UserOutlet::find()->where(['user_id' => BasicAuth::user()->id])->one();
        if (!$userOutlet) {
            return [
                'message' => 'Outlet not found'
            ];
        }
        $outlet_id = $userOutlet->outlet_id;

        // get order with status CART
        $order = Order::find()->where(['outlet_id' => $outlet_id, 'status' => 'CART'])->one();
        if (!$order) {
            return [
                'message' => 'Active order cart is not found'
            ];
        }

        // get latest order-number in setting
        $setting = Setting::find()->where(['key' => 'order-number'])->one();
        $orderNumber = $setting->value + 1;
        $setting->value = $orderNumber.'';

        // generate order number and update status
        $order->order_number = 'ORD-'.sprintf("%05d", $orderNumber);
        $order->ordered_at = date('Y-m-d H:i:s');
        $order->status = 'ORDER';
        if ($order->save()) {
            $setting->save();

            /**
             * Slack Notification
             * Emoji: https://projects.iamcal.com/emoji-data/table.htm
             */ 
            $content = ":shopping_trolley: *NEW ORDER FROM ".$order->outlet->name."*\n";
            $content .= "\n*ORDER DETAIL:*\n";
            $content .= "Order number = ".$order->order_number."\n";
            $content .= "Outlet = ".$order->outlet->name."\n";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."\n";
            $content .= "Ordered By = ".$order->createdBy->name."\n";
            $content .= "Outlet Note = ".$order->note."\n";
            $content .= "\n*PRODUCT DETAIL:*\n";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." ".$detail->product->unit." x ".Strings::getRupiah($detail->product->price_sale)." = ".Strings::getRupiah($subtotal)."\n";
            }
            $content .= "*TOTAL = ".Strings::getRupiah($total)."*\n\n";
            $content .= "Please process the order immediately. Production admin can still make changes to products and order quantity. Thank you :blush:.";
            Slack::notify($content);

            /**
             * Send email notification to Admin
             * Using KIRIM.EMAIL API
             */
            $from = 'noreply@pt-pus.com';
            $to = Setting::find()->where(['key' => 'admin-email'])->one()->value;
            $subject = 'PT. PUS - New Order From '.$order->outlet->name;
            $content = "Dear Admin, <br><br><strong>NEW ORDER FROM ".$order->outlet->name."</strong><br>";
            $content .= "<br><strong>ORDER DETAIL:</strong><br>";
            $content .= "Order number = ".$order->order_number."<br>";
            $content .= "Outlet = ".$order->outlet->name."<br>";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."<br>";
            $content .= "Ordered By = ".$order->createdBy->name."<br>";
            $content .= "Outlet Note = ".$order->note."<br>";
            $content .= "<br><strong>PRODUCT DETAIL:</strong><br>";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." ".$detail->product->unit." x ".Strings::getRupiah($detail->product->price_sale)." = ".Strings::getRupiah($subtotal)."<br>";
            }
            $content .= "<strong>TOTAL = ".Strings::getRupiah($total)."</strong><br><br>";
            $content .= "Please process the order immediately. Production admin can still make changes to products and order quantity. Thank you.";
            Mail::send($from, $to, $subject, $content);

            return [
                'message' => 'Order '.$order->order_number.' successfully saved. Wait until the admin confirms your order. You can still change your order notes via the order menu.'
            ];
        } else {
            foreach ($order->errors as $error) {
                return [
                    'message' => $error[0]
                ];
            }
        }
    }
}
