<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\helpers\Slack;
use common\helpers\Mail;
use common\models\Setting;
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

class ApiOrderController extends Controller
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

    public function actionIndex($tampilan = 'SEMUA', $name = '')
    {
        $query = Order::find()
            ->where(['!=', 'status', 'CART'])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $order) {
                $arrOrder[] = [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'invoice_number' => $order->invoice_number.'',
                    'status' => $order->status,
                    'note' => $order->note.'',
                    'courier' => $order->shipping_courier.'',
                    'created_at' => date('d M Y', strtotime($order->created_at)),
                    'ordered_at' => date('d M Y', strtotime($order->ordered_at)),
                ];
            }
        } else {
            $arrOrder[] = [
                'message' => 'Order not found'
            ];
        }
        return $arrOrder;
    }

    public function actionView($id = null)
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
            'exist_status' => $order->existStatus,
            'complete_status' => $order->completeStatus,
            'summary' => $order->summary,
        ];
    }

    public function actionUpdate($id)
    {

        $order = Order::findOne($id);
        if (!$order) throw new \yii\web\NotFoundHttpException('Data tidak ditemukan');
        
        $order->courier = Yii::$app->request->post('name');
        $order->shipping_cost = Yii::$app->request->post('ongkir');

        if ($order->save()) {

            return [
                'id' => $order->id,
                'shipping_cost' => $order->shipping_cost,
                'courier' => $order->courier,
            ];
        } else {
            foreach ($order->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionUpdateQuantity($id)
    {

        $orderDetail = OrderDetail::findOne($id);
        $orderDetail->quantity = Yii::$app->request->post('quantity');

        if ($orderDetail->save()) {
            return $orderDetail;
        } else {
            foreach ($orderDetail->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionOrderView($tampilan = 'SEMUA', $name = '', $id = '')
    {
        $totalPrice = 0;
        $details = OrderDetail::find()
            ->where(['order_id' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($details) > 0) {
            foreach ($details as $detail) {
                $arrOrder[] = [
                    'id' => $detail->id.'',
                    'order_id' => $detail->order_id.'',
                    'product_id' => $detail->product_id.'',
                    'product_code' => $detail->product->code,
                    'product_name' => $detail->product->name,
                    'price_sale' => $detail->price_sale,
                    'price_sale_rupiah' => Strings::getRupiah($detail->price_sale),
                    'quantity' => $detail->quantity,
                    'quantity_unit' => $detail->quantity.' '.$detail->product->unit,
                    'status' => $detail->status,
                    'created_at' => date('d M Y', strtotime($detail->created_at)),
                    'created_by' => $detail->createdBy->name,
                    'exist_status' => $detail->existStatus,
                    'complete_status' => $detail->completeStatus,
                ];

            }
        } else {
            $arrOrder[] = [
                'message' => 'Order not found'
            ];
        }
        return $arrOrder;
    }

    public function actionDeleteItem($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }
        $query = OrderDetailItem::findOne($id);
        $item_id = $query->item_id;

        if ($query->delete()) {
            $item = Item::findOne($item_id);
            $item->status = 'ACTIVE';
            $item->save();
            //return $item->status;
            $product = Product::find()->where(['code' => $item->product_code])->one();
            $product->stock = $product->stock + 1;
            $product->save();
        }

        return ['message' => 'Item berhasil dibatalkan'];
    }

    public function actionProductOrder($name = '', $id = null)
    {
        //$id = 11;
        $arrIdproduct = [];
        $details = OrderDetail::find()->where(['order_id' => $id])->all();
        foreach ($details as $detail) {
            $arrIdproduct[] = $detail->product_id;
            // code...
        }

        $query = Product::find()
            ->where(['status' => 'ACTIVE'])
            ->andWhere(['not in', 'stock', 0])
            ->andWhere(['like', 'name', $name])
            ->andWhere(['not in', 'id', $arrIdproduct])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        if (count($query) > 0) {
            foreach ($query as $product) {
                $arrProduct[] = [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => substr($product->name, 0, 30),
                    'category' => $product->productCategory->name,
                    'stock' => $product->stock.'',
                    'price_sale' => Strings::getRupiah($product->price_sale).'',
                    //'price_purchase' => Strings::getRupiah($product->price_purchase).'',

                    //'status' => $product->status_aktif,
                ];
            }
        } else {
            $arrProduct[] = [
                'message' => 'Product not found'
            ];
        }
        return $arrProduct;
    }

    public function actionCreateDetail()
    {
        //find product
        $product = Product::findOne(Yii::$app->request->post('product_id'));
        $order_detail = new OrderDetail();
        $order_detail->order_id = Yii::$app->request->post('order_id');
        $order_detail->product_id = $product->id;
        $order_detail->price_sale = $product->price_sale;
        $order_detail->quantity = Yii::$app->request->post('quantity');
        $order_detail->status = 'PENDING';
        $order_detail->created_by = BasicAuth::user()->id;

        if ($order_detail->save() ) {
            return $order_detail;
        } else {
            foreach ($order_detail->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionNoteUpdate($id)
    {
        $order = Order::findOne($id);
        $order->seller_note = Yii::$app->request->post('note');

        if ($order->save() ) {
            return $order;
        } else {
            foreach ($order->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionDeleteDetail($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }
        $orderDetail = OrderDetail::findOne($id);

        if ($orderDetail->delete()) {
            return ['message' => 'Product deleted successfully'];
        }
    }

    public function actionUpdateFinalize($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }
        $order = Order::findOne($id);
        $order->status = 'FINALIZE';
        $order->finalized_at = date("Y-m-d H:i:s");
        $order->finalized_by = BasicAuth::user()->id;

        if ($order->save()) {

            // Slack notification
            // Emoji: https://projects.iamcal.com/emoji-data/table.htm
            $content = ":shopping_trolley: *ORDER FINALIZED*\n";
            $content .= "\n*ORDER DETAIL:*\n";
            $content .= "Order number = ".$order->order_number."\n";
            $content .= "Outlet = ".$order->outlet->name."\n";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."\n";
            $content .= "Ordered By = ".$order->createdBy->name."\n";
            $content .= "Outlet Note = ".$order->note."\n";
            $content .= "Seller Note = ".$order->seller_note."\n";
            $content .= "\n*PRODUCT DETAIL:*\n";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." ".$detail->product->unit." x ".Strings::getRupiah($detail->product->price_sale)." = ".Strings::getRupiah($subtotal)."\n";
            }
            $content .= "*TOTAL = ".Strings::getRupiah($total)."*\n\n";
            $content .= "You can start scanning and packing the ordered products. After all products scanned, invoice can be generated. Thank you :blush:.";
            Slack::notify($content);

            /**
             * Send email notification to Outlet
             * Using KIRIM.EMAIL API
             */
            $from = 'noreply@pt-pus.com';
            $to = $order->outlet->email;
            $subject = 'PT. PUS - Order '.$order->order_number.' Finalized';
            $content = "Dear ".$order->outlet->name.", <br><br><strong>Your order ".$order->order_number." has been finalized by Admin. This is your order detail.</strong><br>";
            $content .= "<br><strong>ORDER DETAIL:</strong><br>";
            $content .= "Order number = ".$order->order_number."<br>";
            $content .= "Outlet = ".$order->outlet->name."<br>";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."<br>";
            $content .= "Ordered By = ".$order->createdBy->name."<br>";
            $content .= "Outlet Note = ".$order->note."<br>";
            $content .= "Seller Note = ".$order->seller_note."<br>";
            $content .= "<br><strong>PRODUCT DETAIL:</strong><br>";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." ".$detail->product->unit." x ".Strings::getRupiah($detail->product->price_sale)." = ".Strings::getRupiah($subtotal)."<br>";
            }
            $content .= "<strong>TOTAL = ".Strings::getRupiah($total)."</strong><br><br>";
            $content .= "Wait until your order is delivered and invoice created. Thank you.";
            Mail::send($from, $to, $subject, $content);

            return $order;
        } else {
            foreach ($order->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionScanItem($tampilan = 'SEMUA', $id = '')
    {
        //$prod = Production::findOne($id);
        $orderDetailItems = OrderDetailItem::find()
            ->where(['order_detail_id' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($orderDetailItems) > 0) {
            foreach ($orderDetailItems as $orderDetailItem) {
                $arrScanItem[] = [
                    'id' => $orderDetailItem->id,
                    'product_name' => $orderDetailItem->orderDetail->product->name,
                    'item_code' => $orderDetailItem->item->code,
                    'exp_date' => date('d M Y', strtotime($orderDetailItem->item->expired_date)),
                    'price_purchase' => Strings::getRupiah($orderDetailItem->price_purchase),
                    'price_sale' => Strings::getRupiah($orderDetailItem->price_sale),
                    'status' => $orderDetailItem->status,
                ];
            }
        } else {
            $arrScanItem[] = [
                'message' => 'Item not found'
            ];
        }
        return $arrScanItem;
    }

    public function actionAddItem()
    {
        //check quantity in recipe
        $orderDetail = OrderDetail::findOne(Yii::$app->request->post('order_detail_id'));

        // update scanned item to NONACTIVE
        $item_code = Yii::$app->request->post('item_code');
        $item = Item::find()->where(['code' => $item_code, 'status' => 'ACTIVE'])->one();
        if (!$item) {
            return [
                'message' => 'Product with code '.$item_code.' is not found'
            ];
        }

        if ($orderDetail->product_id != $item->product_id) {
            return [
                'message' => 'Product with code '.$item_code.' are not '.$item->product->name.' products'
            ];
        }

        // decrease product stock
        $product = Product::find()->where(['code' => $item->product_code])->one();
        $stock = $product->stock;

        $orderDetailItems = OrderDetailItem::find()
            ->where(['order_detail_id' => $orderDetail->id])
            ->all();

        if (count($orderDetailItems) >= $orderDetail->quantity) {
            return [
                'message' => 'The item quantity is more than required'
            ];
        } else {
            $orderDetailItem = new OrderDetailItem();
            $orderDetailItem->order_detail_id = Yii::$app->request->post('order_detail_id');
            $orderDetailItem->item_id = $item->id;
            $orderDetailItem->price_purchase = $item->price_purchase;
            $orderDetailItem->price_sale = $product->price_sale;
            $orderDetailItem->status = 'PACKED';
            $orderDetailItem->created_by = BasicAuth::user()->id;

            $checkItem = OrderDetailItem::find()->where(['item_id' => $orderDetailItem->item_id])->one();
            if ($checkItem) {
                return [
                    'message' => 'This item is already in the list'
                ];
            }

            if ($orderDetailItem->save() ) {
                $item->status = 'NONACTIVE';
                if ($item->save()) {
                    // decrease product stock
                    $product->stock = $stock - 1;
                    if ($product->save()) {
                        return $orderDetailItem;
                    } else {
                        return [
                            'message' => 'Product stock failed to save'
                        ];
                    }
                } else {
                    return [
                        'message' => 'Item failed to save'
                    ];
                }
            } else {
                foreach ($orderDetailItem->errors as $error) {
                    throw new \yii\web\ServerErrorHttpException($error[0]);
                }
            }

        }
    }

    public function actionUpdateShipping($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }
        // get latest invoice number from setting
        $setting = Setting::find()->where(['key' => 'invoice-number'])->one();
        $invoiceNumber = $setting->value + 1;
        $setting->value = $invoiceNumber.'';

        $order = Order::findOne($id);
        $order->shipping_cost = Yii::$app->request->post('shipping_cost');
        $order->shipping_courier = Yii::$app->request->post('shipping_courier');
        $order->tax = Yii::$app->request->post('tax');
        $order->tax_description = Yii::$app->request->post('tax_description');
        $order->status = 'SENT';
        if ($order->invoice_number == null){
            $order->invoice_number = 'INV-'.sprintf("%05d", $invoiceNumber);
        }
        

        if ($order->save()) {

            // Slack notification
            // Emoji: https://projects.iamcal.com/emoji-data/table.htm
            $content = ":shopping_trolley: *INVOICE GENERATED*\n";
            $content .= "\n*ORDER DETAIL:*\n";
            $content .= "Order number = ".$order->order_number."\n";
            $content .= "Invoice number = ".$order->invoice_number."\n";
            $content .= "Outlet = ".$order->outlet->name."\n";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."\n";
            $content .= "Ordered By = ".$order->createdBy->name."\n";
            $content .= "Outlet Note = ".$order->note."\n";
            $content .= "Seller Note = ".$order->seller_note."\n";
            $content .= "\n*PRODUCT DETAIL:*\n";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." ".$detail->product->unit." x ".Strings::getRupiah($detail->product->price_sale)." = ".Strings::getRupiah($subtotal)."\n";
            }
            $content .= "\nDelivery cost = ".$order->shippingCostRupiah."\n";
            $content .= "Courier = ".$order->shipping_courier."\n";
            $content .= "Tax = ".$order->taxRupiah."\n";
            $content .= "*TOTAL = ".Strings::getRupiah($total+$order->shipping_cost+$order->tax)."*\n\n";
            Slack::notify($content);

            /**
             * Send email notification to Outlet
             * Using KIRIM.EMAIL API
             */
            $from = 'noreply@pt-pus.com';
            $to = $order->outlet->email;
            $subject = 'PT. PUS - Invoice '.$order->invoice_number.' For You';
            $content = "Dear ".$order->outlet->name.", <br><br><strong>Your invoice for order ".$order->order_number." has been generated. This is your order detail.</strong><br>";
            $content .= "<br><strong>ORDER DETAIL:</strong><br>";
            $content .= "Order number = ".$order->order_number."<br>";
            $content .= "Outlet = ".$order->outlet->name."<br>";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."<br>";
            $content .= "Ordered By = ".$order->createdBy->name."<br>";
            $content .= "Outlet Note = ".$order->note."<br>";
            $content .= "Seller Note = ".$order->seller_note."<br>";
            $content .= "<br><strong>PRODUCT DETAIL:</strong><br>";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." ".$detail->product->unit." x ".Strings::getRupiah($detail->product->price_sale)." = ".Strings::getRupiah($subtotal)."<br>";
            }
            $content .= "<br>Delivery cost = ".$order->shippingCostRupiah."<br>";
            $content .= "Courier = ".$order->shipping_courier."<br>";
            $content .= "Tax = ".$order->taxRupiah."<br>";
            $content .= "<strong>TOTAL = ".Strings::getRupiah($total+$order->shipping_cost+$order->tax)."</strong><br><br>";
            $content .= "You can download invoice here: <a href='https://pt-pus.com/api/outlet/webview/invoice/".$order->invoice_number."/download' download=true>".$order->invoice_number."</a><br>";
            $content .= "Please make a payment to this invoice before the due date. Thank you.";
            Mail::send($from, $to, $subject, $content);

            $setting->save();
            return $order;
        } else {
            foreach ($order->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionUpdatePayment($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }
        $order = Order::findOne($id);
        $order->status = 'PAID';
        $order->paid_at = date("Y-m-d H:i:s");

        if ($order->save()) {

            // Slack notification
            // Emoji: https://projects.iamcal.com/emoji-data/table.htm
            $content = ":shopping_trolley: *INVOICE ".$order->invoice_number." HAS BEEN PAID*\n";
            $content .= "\n*INVOICE DETAIL:*\n";
            $content .= "Order number = ".$order->order_number."\n";
            $content .= "Invoice number = ".$order->invoice_number."\n";
            $content .= "Outlet = ".$order->outlet->name."\n";
            $content .= "Ordered Date = ".date('d M Y', strtotime($order->ordered_at))."\n";
            $content .= "Ordered By = ".$order->createdBy->name."\n";
            $content .= "Outlet Note = ".$order->note."\n";
            $content .= "Seller Note = ".$order->seller_note."\n";
            $content .= "Payment Date = ".date('d M Y', strtotime($order->paid_at))."\n";
            $num = 1;
            $total = 0;
            foreach ($order->orderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->product->price_sale;
                $total += $subtotal;
            }
            $content .= "\nDelivery cost = ".$order->shippingCostRupiah."\n";
            $content .= "Courier = ".$order->shipping_courier."\n";
            $content .= "Tax = ".$order->taxRupiah."\n";
            $content .= "*TOTAL = ".Strings::getRupiah($total+$order->shipping_cost+$order->tax)."*\n\n";
            Slack::notify($content);

            return $order;
        } else {
            foreach ($order->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }
}
