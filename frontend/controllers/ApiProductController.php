<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductionItem;
use common\models\Item;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiProductController extends Controller
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

    public function actionProductCreate()
    {

        if (!Yii::$app->request->post('kode') || !Yii::$app->request->post('nama')) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }

        $product = new Product();
        $product->user_id = BasicAuth::user()->id;
        $product->kode = Yii::$app->request->post('kode');
        $product->nama = Yii::$app->request->post('nama');
        $product->provinsi_id = intval(Yii::$app->request->post('provinsi_id'));
        $product->kota_id = intval(Yii::$app->request->post('kota_id'));
        $product->alamat = Yii::$app->request->post('alamat');
        $product->kode_pos = Yii::$app->request->post('kode_pos');
        $product->pic = Yii::$app->request->post('pic');
        $product->telepon = Yii::$app->request->post('telepon');
        $product->handphone = Yii::$app->request->post('handphone');
        $product->email = Yii::$app->request->post('email');
        $product->website = Yii::$app->request->post('website');
        $product->kategori_pelanggan = Yii::$app->request->post('kategori_pelanggan');
        $product->rute_kunjungan = Yii::$app->request->post('rute_kunjungan');
        $product->hari_kunjungan = Yii::$app->request->post('hari_kunjungan');
        $product->tipe_kunjungan = Yii::$app->request->post('tipe_kunjungan');
        $product->catatan = Yii::$app->request->post('catatan');

        if (Yii::$app->request->post('gambar') !== '') {
            // random generated name
            $randomName = Yii::$app->security->generateRandomString() . '.jpg';
            $newRandomName = Yii::$app->security->generateRandomString() . '.jpg';
            // write gambar ke server
            $ifp = fopen(Yii::getAlias('@uploads/product/' . $randomName), 'wb');
            $decodedBase64 = base64_decode(Yii::$app->request->post('gambar'));
            fwrite($ifp, $decodedBase64);
            fclose($ifp);
            // memastikan thumbnail besar memiliki rasio 1:1 (square) dan maksimal 500x500
            // jika ukuran kurang dari 500x500, maka dipilih ukuran terkecil agar rasionya tetap square
            $path = Yii::getAlias('@uploads/product/' . $randomName);
            $imageSize = getimagesize($path);
            if ($imageSize[0] >= 500 && $imageSize[1] >= 500) {
                Image::thumbnail($path, 500, 500)->save(Yii::getAlias('@uploads/product/' . $newRandomName), ['quality' => 100]);
            } else if ($imageSize[0] <= 500 || $imageSize[1] <= 500) {
                if ($imageSize[0] < $imageSize[1]) {
                    Image::thumbnail($path, $imageSize[0], $imageSize[0])->save(Yii::getAlias('@uploads/product/' . $newRandomName), ['quality' => 100]);
                } else {
                    Image::thumbnail($path, $imageSize[1], $imageSize[1])->save(Yii::getAlias('@uploads/product/' . $newRandomName), ['quality' => 100]);
                }
            }
            $product->gambar = $newRandomName;
        }

        if ($product->save()) {
            return $product;
        } else {
            foreach ($product->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionProductUpdate($id)
    {

        if (!Yii::$app->request->post('kode') || !Yii::$app->request->post('nama')) {
            throw new \yii\web\BadRequestHttpException('Parameter tidak lengkap');
        }

        $product = Product::findOne($id);
        if (!$product) throw new \yii\web\NotFoundHttpException('Data tidak ditemukan');
        $oldGambar = $product->gambar;

        $product->kode = Yii::$app->request->post('kode');
        $product->nama = Yii::$app->request->post('nama');
        $product->provinsi_id = intval(Yii::$app->request->post('provinsi_id'));
        $product->kota_id = intval(Yii::$app->request->post('kota_id'));
        $product->alamat = Yii::$app->request->post('alamat');
        $product->kode_pos = Yii::$app->request->post('kode_pos');
        $product->pic = Yii::$app->request->post('pic');
        $product->telepon = Yii::$app->request->post('telepon');
        $product->handphone = Yii::$app->request->post('handphone');
        $product->email = Yii::$app->request->post('email');
        $product->website = Yii::$app->request->post('website');
        $product->kategori_pelanggan = Yii::$app->request->post('kategori_pelanggan');
        $product->rute_kunjungan = Yii::$app->request->post('rute_kunjungan');
        $product->hari_kunjungan = Yii::$app->request->post('hari_kunjungan');
        $product->tipe_kunjungan = Yii::$app->request->post('tipe_kunjungan');
        $product->catatan = Yii::$app->request->post('catatan');

        // handle base64 image
        if (Yii::$app->request->post('gambar') !== '') {
            // random generated name
            $randomName = Yii::$app->security->generateRandomString() . '.jpg';
            $newRandomName = Yii::$app->security->generateRandomString() . '.jpg';
            // write gambar ke server
            $ifp = fopen(Yii::getAlias('@uploads/product/' . $randomName), 'wb');
            $decodedBase64 = base64_decode(Yii::$app->request->post('gambar'));
            fwrite($ifp, $decodedBase64);
            fclose($ifp);
            // memastikan thumbnail besar memiliki rasio 1:1 (square) dan maksimal 500x500
            // jika ukuran kurang dari 500x500, maka dipilih ukuran terkecil agar rasionya tetap square
            $path = Yii::getAlias('@uploads/product/' . $randomName);
            $imageSize = getimagesize($path);
            if ($imageSize[0] >= 500 && $imageSize[1] >= 500) {
                Image::thumbnail($path, 500, 500)->save(Yii::getAlias('@uploads/product/' . $newRandomName), ['quality' => 100]);
            } else if ($imageSize[0] <= 500 || $imageSize[1] <= 500) {
                if ($imageSize[0] < $imageSize[1]) {
                    Image::thumbnail($path, $imageSize[0], $imageSize[0])->save(Yii::getAlias('@uploads/product/' . $newRandomName), ['quality' => 100]);
                } else {
                    Image::thumbnail($path, $imageSize[1], $imageSize[1])->save(Yii::getAlias('@uploads/product/' . $newRandomName), ['quality' => 100]);
                }
            }
            $product->gambar = $newRandomName;

            // hapus gambar lama
            @unlink(Yii::getAlias('@uploads/product/' . $oldGambar));
        } else {
            $product->gambar = $oldGambar;
            // hapus gambar lama
            // @unlink(Yii::getAlias('@uploads/product/' . $oldGambar));
        }

        if ($product->save()) {
            return $product;
        } else {
            foreach ($product->errors as $error) {
                throw new \yii\web\ServerErrorHttpException($error[0]);
            }
        }
    }

    public function actionIndex($tampilan = 'All', $filter = 'All', $name = '')
    {
        if ($filter == 'All') {
            // code...
            $query = Product::find()
                ->where(['status' => 'ACTIVE'])
                ->andWhere(['like', 'name', $name])
                ->orderBy(['id' => SORT_DESC])
                // ->limit(30)
                ->all();
            
            if (count($query) > 0) {
                foreach ($query as $product) {
                    $arrProduct[] = [
                        'id' => $product->id,
                        'code' => $product->code,
                        'name' => $product->name,
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
        } else {
            $kategori = ProductCategory::find()->where(['name' => $filter])->one();
            $query = Product::find()
                ->where(['status' => 'ACTIVE'])
                ->andWhere(['product_category_id' => $kategori->id])
                ->andWhere(['like', 'name', $name])
                ->orderBy(['id' => SORT_DESC])
                ->all();
            
            if (count($query) > 0) {
                foreach ($query as $product) {
                    $arrProduct[] = [
                        'id' => $product->id,
                        'code' => $product->code,
                        'name' => $product->name,
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

        
    }

    public function actionView($id = null)
    {
        $product = Product::find()->where(['code' => $id])->one();
        if (!$product) {
            return [
                'message' => 'Product with code '.$id.' is not found'
            ];
        }
        
        $arrProduct = [
            'id' => $product->id,
            'code' => $product->code,
            'category' => $product->productCategory->name,
            'name' => $product->name,
            'description' => $product->description.'',
            'stock' => $product->stock.' '.$product->unit,
            'price_sale' => Strings::getRupiah($product->price_sale).'',
            'last_opname_at' => $product->lastOpnameAt.'',
        ];

        return $arrProduct;
    }

    public function actionCategory($id = null)
    {
        $productCategories = ProductCategory::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $arrCategory[] = [
            'id' => 0,
            'name' => 'All',
        ];
        
        if (count($productCategories) > 0) {
            foreach ($productCategories as $category) {
                if (count($category->products)>0) {
                    $arrCategory[] = [
                        'id' => $category->id,
                        'name' => $category->name,
                    ];
                }
            }
        } else {
            $arrCategory[] = [
                'message' => 'Category not found'
            ];
        }
        return $arrCategory;
    }

    public function actionItems($code = null)
    {
        //return Product::findOne($id);
        $query = Item::find()
            ->where(['product_code' => $code])
            ->andWhere(['status' => 'ACTIVE'])->all();
        
        if (count($query) > 0) {
            foreach ($query as $item) {
                $arrItem[] = [
                    'product_name' => $item->product->name,
                    'item_name' => $item->name,
                    'code' => $item->code,
                    'base_amount' => $item->base_amount.' '.$item->unit,
                    'amount' => $item->amount.' '.$item->unit,
                    'price_sale' => Strings::getRupiah($item->product->price_sale).'',
                    'expired_date' => date('d M Y', strtotime($item->expired_date)),
                ];
            }
        } else {
            $arrItem[] = [
                'message' => 'Item product not found'
            ];
        }
        return $arrItem;
    }

    public function actionItemView($code = null)
    {
        $item = Item::find()->where(['code' => $code, 'status' => 'ACTIVE'])->one();
        if (!$item) {
            return [
                'message' => 'Item is not found'
            ];
        }

        $arrItem = [
            'code' => $item->code,
            'product_code' => $item->product_code,
            'product_name' => $item->product->name,
            'stock' => $item->product->stock.' '.$item->product->unit,
            'item_name' => $item->name,
            'amount' => $item->amount.'',
            'base_amount' => $item->base_amount.'',
            'unit' => $item->unit,
            'price_sale' => $item->product->price_sale.'',
            'price_sale_rupiah' => Strings::getRupiah($item->product->price_sale),
            'expired_date' => date('d M Y', strtotime($item->expired_date)),
            
            // purchase information
            'purchase_order_id' => $item->purchase_order_id.'',
            'price_purchase' => $item->purchase_order_id ? $item->price_purchase.'' : '',
            'price_purchase_rupiah' => $item->purchase_order_id ? Strings::getRupiah($item->price_purchase) : '',
            'supplier_name' => $item->purchase_order_id ? $item->purchaseOrder->supplier->name : '',
            'po_number' => $item->purchase_order_id ? $item->purchaseOrder->po_number : '',
            'purchased_at' => $item->purchase_order_id ? date('d M Y', strtotime($item->purchaseOrder->created_at)) : '',
            'purchased_by' => $item->purchase_order_id ? $item->purchaseOrder->createdBy->name : '',
            
            // production information
            'production_id' => $item->production_id.'',
            'production_cost' => $item->production_id ? $item->price_purchase.'' : '',
            'production_cost_rupiah' => $item->production_id ? Strings::getRupiah($item->price_purchase) : '',
            'production_number' => $item->production_id ? $item->production->production_number : '',
            'produced_at' => $item->production_id ? date('d M Y', strtotime($item->production->created_at)) : '',
            'produced_by' => $item->production_id ? $item->production->createdBy->name : '',

        ];

        return $arrItem;
    }

    public function actionItemUsages($code = null)
    {
        $item = Item::find()->where(['code' => $code])->one();
        $items = ProductionItem::find()
            ->where(['item_id' => $item->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(20)
            ->all();

        $arrItem = [];
        if (count($items) > 0) {
            foreach ($items as $item) {
                $arrItem[] = [
                    'id' => $item->id.'',
                    'production_number' => $item->production->production_number,
                    'product_name' => $item->production->recipe->product->name,
                    'usage' => $item->amount.' '.$item->unit,
                    'created_at' => date('d M Y H:i:s', strtotime($item->production->created_at)),
                    'created_by' => $item->production->createdBy->name,
                ];
            }
        } else {
            $arrItem[] = [
                'message' => 'This item is never used for production'
            ];
        }
        return $arrItem;
    }
}
