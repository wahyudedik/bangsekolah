<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;
use common\helpers\Strings;
use common\helpers\BasicAuth;
use common\models\User;
use common\models\Product;
use common\models\Outlet;
use common\models\UserOutlet;
use common\models\ProductCategory;
use common\models\ProductOutlet;
use common\models\Item;
use common\models\Order;
use yii\data\ActiveDataProvider;
use DateTime;
use DatePeriod;
use DateInterval;
use yii\db\Query;

class ApiOutletProductController extends Controller
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

    public function actionIndex($view = 'All', $filter = 'All', $name = '')
    {

        $user_id = BasicAuth::user()->id;
        $userOutlet = UserOutlet::find()->where(['user_id' => $user_id])->one();
        if (!$userOutlet) {
            return [[
                'message' => 'User outlet not found'
            ]];
        }

        $arrProduct = [];
        $productOutlets = ProductOutlet::find()
            ->where(['outlet_id' => $userOutlet->outlet_id])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        if (count($productOutlets) > 0) {
            foreach ($productOutlets as $productOutlet) {
                if ($filter == 'All') {
                    if ($name) {
                        $product = Product::find()
                        ->where(['id' => $productOutlet->product_id])
                        ->andWhere(['like', 'name', $name])
                        ->andWhere(['status' => 'ACTIVE'])
                        ->one();
                    } else {
                        $product = Product::find()
                        ->where(['id' => $productOutlet->product_id])
                        ->andWhere(['status' => 'ACTIVE'])
                        ->one();
                    }
                } else {
                    $category = ProductCategory::find()->where(['name' => $filter])->one();
                    if (!$category) {
                        return [[
                            'message' => 'Category is not found'
                        ]];
                    }
                    if ($name) {
                        $product = Product::find()
                        ->where(['id' => $productOutlet->product_id])
                        ->andWhere(['like', 'name', $name])
                        ->andWhere(['product_category_id' => $category->id])
                        ->andWhere(['status' => 'ACTIVE'])
                        ->one();
                    } else {
                        $product = Product::find()
                        ->where(['id' => $productOutlet->product_id])
                        ->andWhere(['product_category_id' => $category->id])
                        ->andWhere(['status' => 'ACTIVE'])
                        ->one();
                    }
                }

                // check if product is in the active CART
                $productExists = false;
                $order = Order::find()->where(['outlet_id' => $userOutlet->outlet_id, 'status' => 'CART'])->one();
                if ($order && $product) {
                    foreach ($order->orderDetails as $detail) {
                        if ($detail->product_id == $product->id) {
                            $productExists = true;
                            break;
                        }
                    }
                }

                if ($product && !$productExists) {
                    $arrProduct[] = [
                        'id' => $product->id,
                        'code' => $product->code,
                        'name' => Strings::sortText($product->name, 20),
                        'category' => $product->productCategory->name,
                        'stock' => $product->stock.' '.$product->unit,
                        'price_sale' => Strings::getRupiah($product->price_sale).'',
                    ];
                }
            }
        } else {
            return  [[
                'message' => 'Product not found'
            ]];
        }

        if (count($arrProduct)==0) {
            return [[
                'message' => 'Product not found'
            ]];
        }
        return $arrProduct;
    }

    public function actionView($id = null)
    {
        //return Product::findOne($id);
        $product = Product::find()->where(['code' => $id])->one();
        
        $arrProduct = [
            'id' => $product->id.'',
            'code' => $product->code,
            'category' => $product->productCategory->name,
            'name' => $product->name,
            'description' => $product->description.'',
            'stock' => $product->stock.' '.$product->unit,
            'price_sale' => Strings::getRupiah($product->price_sale).'',
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
}
