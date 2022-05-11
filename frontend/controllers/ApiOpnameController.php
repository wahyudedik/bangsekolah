<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Setting;
use common\models\Product;
use common\models\Item;
use common\models\StockOpname;
use common\models\StockOpnameDetail;
use common\models\StockOpnameDetailItem;
use common\models\StockOpnameDeletedItem;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiOpnameController extends Controller
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
        $query = StockOpname::find()
            ->where(['like', 'so_number', $name])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $opname) {
                $arrOpname[] = [
                    'id' => $opname->id,
                    'so_number' => $opname->so_number,
                    'status' => $opname->status,
                    'created_by' => $opname->createdBy->name,
                    'created_at' => date('d M Y', strtotime($opname->created_at)),
                    //'created_at' => $opname->created_at,
                ];
            }
        } else {
            $arrOpname[] = [
                'message' => 'Stock opname not found'
            ];
        }
        return $arrOpname;
    }

    public function actionCreate()
    {
        //for so-number
        $setting = Setting::find()->where(['key' => 'so-number'])->one();
        $sonumber = $setting->value + 1;
        $setting->value = $sonumber.'';
        //return $setting->save();

        //create stock opname
        $opname = new StockOpname();
        $opname->created_by = BasicAuth::user()->id;
        $opname->status = 'DRAFT';
        $opname->so_number = 'SO-'.sprintf("%05d", $sonumber);

        if ($opname->save() ) {
            $setting->save();
            return $opname;
        } else {
            foreach ($opname->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionView($id = null){
        $opname = StockOpname::findOne($id);
        
        $arrOpname = [
            'so_number' => $opname->so_number,
            'status' => $opname->status,
            'created_at' => Strings::getFullDate($opname->created_at),
            'created_by' => $opname->createdBy->name,
            'complete_status' => $opname->completeStatus,
        ];

        return $arrOpname;
    }

    public function actionUpdateProcess($id){
        $opname = StockOpname::findOne($id);
        $opname->status = Yii::$app->request->post('status');
        
        if ($opname->save() ) {
            return $opname;
        } else {
            foreach ($opname->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionUpdate($id){
        $opname = StockOpname::findOne($id);
        $opname->status = Yii::$app->request->post('status');
        
        if ($opname->save() ) {
            return $opname;
        } else {
            foreach ($opname->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionUpdateDetail($id){
        $opnameDetail = StockOpnameDetail::findOne($id);

        if($opnameDetail->stock_product == $opnameDetail->stock_opname){
            $opnameDetail->status = 'MATCH';
        } else {
            $opnameDetail->status = 'NOT_MATCH';
        }
        
        if ($opnameDetail->save() ) {
            return $opnameDetail;
        } else {
            foreach ($opnameDetail->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionOpnameDetail($id = null)
    {
        //return Product::findOne($id);
        $query = StockOpnameDetail::find()->where(['stock_opname_id' => $id])->all();
        
        if (count($query) > 0) {
            foreach ($query as $stock_opname) {
                $arrOpname[] = [
                    'id' => $stock_opname->id,
                    'product_id' => $stock_opname->product_id,
                    'product_name' => $stock_opname->product->name,
                    'stock_product' => $stock_opname->stock_product,
                    'stock_opname' => $stock_opname->stock_opname,
                    'status' => $stock_opname->status,
                    'exist_status' => $stock_opname->existStatus,
                    'checked_at' => $stock_opname->checked_at,
                    ''
                ];
            }
        } else {
            $arrOpname[] = [
                'message' => 'Ingredients not found'
            ];
        }
        return $arrOpname;
    }

    public function actionProductOpname($name = '', $id = null)
    {
        //$id = 11;
        $arrIdproduct = [];
        $details = StockOpnameDetail::find()->where(['stock_opname_id' => $id])->all();
        foreach ($details as $detail) {
            $arrIdproduct[] = $detail->product_id;
            // code...
        }

        $query = Product::find()
            ->where(['status' => 'ACTIVE'])
            ->andWhere(['like', 'name', $name])
            ->andWhere(['not in', 'id', $arrIdproduct])
            ->orderBy(['name' => SORT_ASC])
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
        $opname_detail = new StockOpnameDetail();
        $opname_detail->stock_opname_id = Yii::$app->request->post('opname_id');
        $opname_detail->product_id = $product->id;
        $opname_detail->stock_product = $product->stock;
        $opname_detail->stock_opname = 0;
        $opname_detail->status = 'PENDING';

        if ($opname_detail->save() ) {
            //create stock opname detail item
            $items = Item::find()
                ->where(['product_id' => $opname_detail->product_id])
                ->andWhere(['status' => 'ACTIVE'])
                ->all();
            foreach ($items as $item) {
                $opname_item = new StockOpnameDetailItem();
                $opname_item->stock_opname_detail_id = $opname_detail->id;
                $opname_item->item_id = $item->id;
                $opname_item->product_id = $item->product_id;
                $opname_item->code = $item->code;
                $opname_item->amount_before = $item->amount;
                $opname_item->status = 'NOT_EXIST';
                $opname_item->save();
            }
            return $opname_detail;
        } else {
            foreach ($opname_detail->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionListItem($tampilan = 'SEMUA', $id = '', $idproduct = '')
    {
        //$prod = Production::findOne($id);
        $query = StockOpnameDetailItem::find()
            ->where(['stock_opname_detail_id' => $id])
            ->andWhere(['product_id' => $idproduct])
            ->orderBy(['scanned_at' => SORT_DESC])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $opname_item) {
                $arrOpnameitem[] = [
                    'id' => $opname_item->id,
                    'product' => $opname_item->product->name,
                    'item_code' => $opname_item->code,
                    'exp_date' => $opname_item->item->expired_date,
                    'amount_before' => $opname_item->amount_before.'',
                    'amount_after' => $opname_item->amount_after.'',
                    'amount_unit' => $opname_item->item->unit,
                    'status' => $opname_item->status,
                    'exist_status' => $opname_item->existStatus,
                    'scanned_at' => date('d M Y H:i:s', strtotime($opname_item->scanned_at)),
                ];
            }
        } else {
            $arrOpnameitem[] = [
                'message' => 'Item not found'
            ];
        }
        return $arrOpnameitem;
    }

    public function actionScanItem()
    {
        // scan item
        $itemAlreadyScanned = false;
        $item_code = Yii::$app->request->post('item_id');
        $opname_item = StockOpnameDetailItem::find()
            ->where(['code' => $item_code])
            ->andWhere(['stock_opname_detail_id' => Yii::$app->request->post('opname_detail_id')])
            ->one();
        if ($opname_item->status == 'EXIST') {
            $itemAlreadyScanned = true;
        }

        //check item yang di scan sesuai produk atau tidak?
        if ($opname_item->product_id != Yii::$app->request->post('product_id')) {
            $opname_item = [
                'message' => 'Item scanned is not match with product code'
            ];
            return $opname_item;
        } else {
            $opname_item->amount_after = Yii::$app->request->post('amount_after');
            $opname_item->status = 'EXIST';
            $opname_item->save();

            if (!$itemAlreadyScanned) {
                //update item amount
                $item = Item::findOne($opname_item->item_id);
                $item->amount = $opname_item->amount_after;
                $item->save();
                //update stock opname
                $opname_detail = StockOpnameDetail::findOne(Yii::$app->request->post('opname_detail_id'));
                $opname_detail->stock_opname = $opname_detail->stock_opname + 1;
                $opname_detail->save();
            }

            return $opname_detail;
        }

    }

    public function actionDeleteItem($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Missing parameters');
        }
        $detailItem = StockOpnameDetailItem::findOne($id);
        $item_id = $detailItem->item_id;
        $product_id = $detailItem->product_id;

        // before delete detail_item,
        // first insert stock_opname_deleted_item
        $deletedItem = new StockOpnameDeletedItem();
        $deletedItem->stock_opname_detail_id = $detailItem->stock_opname_detail_id;
        $deletedItem->item_id = $detailItem->item_id;
        $deletedItem->product_id = $detailItem->product_id;
        $deletedItem->code = $detailItem->code;
        $deletedItem->reason = Yii::$app->request->post('reason');
        $deletedItem->deleted_at = date('Y-m-d H:i:s');
        $deletedItem->save();

        if ($detailItem->delete()) {
            // delete product item
            $item = Item::findOne($item_id);
            $item->status = 'NONACTIVE';
            if (!$item->save()) {
                return [
                    'message' => 'Item nonactive failed to update'
                ];
            }

            // decrease product live stock
            $product = Product::findOne($product_id);
            $product->stock = $product->stock - 1;
            if (!$product->save()) {
                return [
                    'message' => 'Product stock failed to update'
                ];
            }
        }

        return ['message' => 'Product item successfully deleted'];
    }

    public function actionListDeleted($tampilan = 'SEMUA', $id = '', $idproduct = '')
    {
        //$prod = Production::findOne($id);
        $query = StockOpnameDeletedItem::find()
            ->where(['stock_opname_detail_id' => $id])
            ->andWhere(['product_id' => $idproduct])
            ->all();
        
        if (count($query) > 0) {
            foreach ($query as $opname_item) {
                $arrOpnameitem[] = [
                    'id' => $opname_item->id,
                    'product' => $opname_item->product->name,
                    'item_code' => $opname_item->code,
                    'item_name' => $opname_item->item->name,
                    'exp_date' => $opname_item->item->expired_date,
                    'reason' => $opname_item->reason,
                    'deleted_at' => date('d M Y H:i:s', strtotime($opname_item->deleted_at)),
                ];
            }
        } else {
            $arrOpnameitem[] = [
                'message' => 'Deleted item not found'
            ];
        }
        return $arrOpnameitem;
    }

    public function actionFindItem($id = null, $opname_detail_id = null)
    {
        $item_code = $id;
        $opname_item = StockOpnameDetailItem::find()
            ->where(['code' => $item_code])
            ->andWhere(['stock_opname_detail_id' => $opname_detail_id])
            ->one();
        //$detail_item = StockOpnameDetailItem::findOne($id);

        return $opname_item;
    }

    public function actionDelete($id = null)
    {
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('Missing parameters');
        }
        $opname = StockOpname::findOne($id);

        if ($opname->delete()) {
            return ['message' => 'Stock Opname successfully deleted'];
        } else {
            return ['message' => 'Stock Opname failed to delete'];
        }

        
    }
}
