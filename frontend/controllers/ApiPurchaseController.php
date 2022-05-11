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
use common\models\Item;
use common\models\Product;
use common\models\Setting;
use common\models\PurchaseOrder;
use common\models\PurchaseOrderDetail;
use common\models\PurchaseOrderDetailItem;

use DateTime;
use DatePeriod;
use DateInterval;

class ApiPurchaseController extends Controller
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
        $query = PurchaseOrder::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $purchase) {
                $arrPurchase[] = [
                    'id' => $purchase->id,
                    'supplier' => $purchase->supplier_id == null ? '' : $purchase->supplier->name,
                    'po_number' => $purchase->po_number,
                    'status' => $purchase->status,
                    'created_at' => date('d M Y', strtotime($purchase->created_at)),
                ];
            }
        } else {
            $arrPurchase[] = [
                'message' => 'Purchase not found'
            ];
        }
        return $arrPurchase;
    }

    public function actionView($id = null)
    {
        //return Product::findOne($id);
        $purchase = PurchaseOrder::findOne($id);
        
        $arrPurchase = [
            'supplier' => $purchase->supplier->name,
            'po_number' => $purchase->po_number,
            'status' => $purchase->status,
            'note' => $purchase->note,
            'created_by' => $purchase->createdBy->name,
            'created_at' => date('d M Y', strtotime($purchase->created_at)),
        ];

        return $arrPurchase;
    }

    public function actionCreate()
    {
        //for po-number
        $setting = Setting::find()->where(['key' => 'po-number'])->one();
        $ponumber = $setting->value + 1;
        $setting->value = $ponumber.'';
        //return $setting->save();

        //create purchase
        $purchase = new PurchaseOrder();
        $purchase->created_by = BasicAuth::user()->id;
        $purchase->status = 'DRAFT';
        $purchase->po_number = 'PO-'.sprintf("%05d", $ponumber);

        if ($purchase->save() ) {
            $setting->save();
            return $purchase;
        } else {
            foreach ($purchase->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionUpdateSupplier($id)
    {
        $purchase = PurchaseOrder::findOne($id);
        $purchase->supplier_id = Yii::$app->request->post('supplier_id');
        $purchase->note = Yii::$app->request->post('note');
        $purchase->status = 'DRAFT';

        if ($purchase->save() ) {
            return $purchase;
        } else {
            foreach ($purchase->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionPurchaseDetail($id = null)
    {
        //return Product::findOne($id);
        $query = PurchaseOrderDetail::find()
            ->where(['purchase_order_id' => $id])->all();
        
        if (count($query) > 0) {
            foreach ($query as $podetail) {
                $arrItem[] = [
                    'id' => $podetail->id,
                    'product' => $podetail->product->name,
                    'quantity' => $podetail->quantity,
                    'amount' => $podetail->amount,
                    'unit' => $podetail->unit,
                    'price_purchase' => Strings::getRupiah($podetail->price_purchase).'',
                    'created_at' => $podetail->created_at,
                ];
            }
        } else {
            $arrItem[] = [
                'message' => 'PO Detail not found'
            ];
        }
        return $arrItem;
    }

    public function actionProductPurchase($name = '', $id = null)
    {
        //$id = 11;
        //$arrIdproduct = [];
        $details = PurchaseOrderDetail::find()->where(['purchase_order_id' => $id])->all();
        // foreach ($details as $detail) {
        //     $arrIdproduct[] = $detail->product_id;
        //     // code...
        // }
        // ->andWhere(['not in', 'id', $arrIdproduct])

        $query = Product::find()
            ->where(['status' => 'ACTIVE'])
            ->andWhere(['like', 'name', $name])
            ->orderBy(['id' => SORT_DESC])
            ->limit(30)
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
                ];
            }
        } else {
            $arrProduct[] = [
                'message' => 'Product not found'
            ];
        }
        return $arrProduct;
    }

    public function actionAddProduct()
    {
        $purchaseDetail = new PurchaseOrderDetail();
        $purchaseDetail->purchase_order_id = Yii::$app->request->post('purchase_order_id');
        $purchaseDetail->product_id = Yii::$app->request->post('product_id');
        $purchaseDetail->name = Yii::$app->request->post('name');
        $purchaseDetail->quantity = Yii::$app->request->post('quantity');
        $purchaseDetail->amount = Yii::$app->request->post('amount');
        $purchaseDetail->unit = Yii::$app->request->post('unit');
        $purchaseDetail->price_purchase = Yii::$app->request->post('price_purchase');
        $purchaseDetail->created_by = BasicAuth::user()->id;

        if ($purchaseDetail->save() ) {
            // update purchase status
            $purchase = $purchaseDetail->purchaseOrder;
            $purchase->status = 'PROCESS';
            $purchase->save();

            return $purchaseDetail;
        } else {
            foreach ($purchase->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionPurchaseDetailview($id)
    {
        $purchase = PurchaseOrderDetail::findOne($id);
        if (!$purchase) throw new \yii\web\NotFoundHttpException('Data tidak ditemukan');
        return [
            'id' => $purchase->id,
            'purchase_order_id' => $purchase->purchase_order_id,
            'product_id' => $purchase->product_id,
            'name' => $purchase->name,
            'quantity' => $purchase->quantity,
            'amount' => $purchase->amount,
            'unit' => $purchase->unit,
            'price_purchase' => $purchase->price_purchase,
        ];
    }

    public function actionFinish($id)
    {
        //update purchase order
        $purchase = PurchaseOrder::findOne($id);
        $purchase->status = 'FINISHED';

        if ($purchase->save() ) {
            foreach ($purchase->purchaseOrderDetails as $purchase_detail) {

                for ($i=0; $i < $purchase_detail->quantity; $i++) { 
                    //input table Item
                    $date = strtotime(date("Y-m-d h:i:sa"));
                    $item = new Item();
                    $item->purchase_order_id = $purchase_detail->purchase_order_id;
                    $item->product_id = $purchase_detail->product_id;
                    $item->product_code = $purchase_detail->product->code;
                    $item->code = $purchase_detail->product->code.'-'.strtotime(date("Y-m-d h:i:sa")).$i.'';
                    $item->name = $purchase_detail->name;
                    $item->unit = $purchase_detail->unit;
                    $item->base_amount = $purchase_detail->amount;
                    $item->amount = $purchase_detail->amount;
                    $item->price_purchase = $purchase_detail->price_purchase;
                    $item->expired_date = date("Y-m-d", strtotime("+3 month", $date));;
                    $item->status = 'ACTIVE';
                    $item->created_by = BasicAuth::user()->id;

                    //return date("Y-m-d h:i:sa");
                    if ($item->save()) {
                        //return $item;
                        $purchase_item = new PurchaseOrderDetailItem();
                        $purchase_item->purchase_order_detail_id = $purchase_detail->id;
                        $purchase_item->item_id = $item->id;
                        $purchase_item->product_id = $purchase_detail->product_id;
                        $purchase_item->save();
                    }  else {
                        foreach ($item->errors as $error) {
                            throw new \yii\web\ServerErrorHttpException($error[0]);
                        }
                    }

                }
                //update stock product
                $product = Product::findOne($purchase_detail->product_id);
                $product->stock = $product->stock + $purchase_detail->quantity;
                $product->save();
            }

            // Slack notification
            // Emoji: https://projects.iamcal.com/emoji-data/table.htm
            $content = ":shopping_trolley: *NEW PURCHASE ORDER*\n";
            $content .= "\n*PURCHASE DETAIL:*\n";
            $content .= "Purchase number = ".$purchase->po_number."\n";
            $content .= "Purchase Date = ".date('d M Y', strtotime($purchase->created_at))."\n";
            $content .= "Created By = ".$purchase->createdBy->name."\n";
            $content .= "Admin Note = ".$purchase->note."\n";
            $content .= "\n*PRODUCT LIST:*\n";
            $num = 1;
            $total = 0;
            foreach ($purchase->purchaseOrderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->price_purchase;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." x ".Strings::getRupiah($detail->price_purchase)." ("+$detail->amount+" "+$detail->unit+") = ".Strings::getRupiah($subtotal)."\n";
            }
            $content .= "*TOTAL = ".Strings::getRupiah($total)."*\n\n";
            $content .= "Please make sure each purchased item has been given a barcode so that the item is registered in the database. Thank you :blush:.";
            Slack::notify($content);

            /**
             * Send email notification to Admin
             * Using KIRIM.EMAIL API
             */
            $from = 'noreply@pt-pus.com';
            $to = Setting::find()->where(['key' => 'admin-email'])->one()->value;
            $subject = 'PT. PUS - New Purchase Order '.$purchase->po_number;
            $content = "Dear Admin, <br><br><strong>New purchasing is created. This is the purchase detail.</strong><br>";
            $content .= "<br><strong>PURCHASE DETAIL:</strong><br>";
            $content .= "Purchase number = ".$purchase->po_number."<br>";
            $content .= "Purchase Date = ".date('d M Y', strtotime($purchase->created_at))."<br>";
            $content .= "Created By = ".$purchase->createdBy->name."<br>";
            $content .= "Admin Note = ".$purchase->note."<br>";
            $content .= "<br><strong>PRODUCT LIST:</strong><br>";
            $num = 1;
            $total = 0;
            foreach ($purchase->purchaseOrderDetails as $detail) {
                $subtotal = $detail->quantity*$detail->price_purchase;
                $total += $subtotal;
                $content .= $num++.". ".$detail->product->name.", ".$detail->quantity." x ".Strings::getRupiah($detail->price_purchase)." ("+$detail->amount+" "+$detail->unit+") = ".Strings::getRupiah($subtotal)."<br>";
            }
            $content .= "<strong>TOTAL = ".Strings::getRupiah($total)."</strong><br><br>";
            $content .= "Please make sure each purchased item has been given a barcode so that the item is registered in the database. Thank you.";
            Mail::send($from, $to, $subject, $content);

            return $purchase;
        } else {
            foreach ($purchase->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionPurchaseUpdate($id)
    {
        $purchase = PurchaseOrderDetail::findOne($id);
        $purchase->name = Yii::$app->request->post('name');
        $purchase->quantity = Yii::$app->request->post('quantity');
        $purchase->amount = Yii::$app->request->post('amount');
        $purchase->unit = Yii::$app->request->post('unit');
        $purchase->price_purchase = Yii::$app->request->post('price_purchase');

        if ($purchase->save() ) {
            return $purchase;
        } else {
            foreach ($purchase->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionPurchaseItem($id = null)
    {
        $query = PurchaseOrderDetailItem::find()->where(['purchase_order_detail_id' => $id])->all();

        if (count($query) > 0) {
            foreach ($query as $purchase) {
                $arrPurchase[] = [
                    'code' => $purchase->item->code,
                    'price_purchase' => Strings::getRupiah($purchase->item->price_purchase).'',
                    'expired_date' => date('d M Y', strtotime($purchase->item->expired_date)),
                    'amount' => $purchase->item->amount.'',
                    'unit' => $purchase->item->unit,
                ];
            }
        } else {
            $arrPurchase[] = [
                'message' => 'Item not found'
            ];
        }
        return $arrPurchase;
    }

    public function actionNoteUpdate($id)
    {
        $purchase = PurchaseOrder::findOne($id);
        $purchase->note = Yii::$app->request->post('note');

        if ($purchase->save() ) {
            return $purchase;
        } else {
            foreach ($purchase->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionDelete($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Missing parameters');
        }
        $purchase = PurchaseOrder::findOne($id);

        if ($purchase->delete()) {
            return ['message' => 'Purchase successfully deleted'];
        } else {
            return ['message' => 'Purchase failed to delete'];
        }

        
    }

}
