<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\Strings;

use common\models\User;
use common\models\UserSchool;
use common\models\LoginForm;
use common\models\Setting;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'reset-all-data'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)  {
                            return User::can();
                        }
                    ],
                ],
            ],
            /*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($startdate = null, $enddate = null)
    {
        // return Yii::$app->security->generatePasswordHash('gresik2022');
        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');

        \common\helpers\Sidebar::setActiveMenu('site', 'index');

        // Vars initiate
        if (!$startdate) $startdate = date('Y-m-d', strtotime('-1 month'));
        if (!$enddate) $enddate = date('Y-m-d');
        // $end = new DateTime($enddate);
        // $begin = new DateTime($startdate);

        return $this->render('index', [
            'startdate' => date('d M Y', strtotime($startdate)),
            'enddate' => date('d M Y', strtotime($enddate)),
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        // return Yii::$app->security->generatePasswordHash('hidayah2022');
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user_school = UserSchool::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
            if ($user_school) {
                Yii::$app->session->set('school_id', $user_school->school_id);
                Yii::$app->session->set('school_name', $user_school->school->name);
            } else {
                Yii::$app->session->set('school_id', '0');
                Yii::$app->session->set('school_name', '0');
            }
            // return $this->goBack();
            return $this->redirect(['site/index']);
        } else {
            // $model->login();
            return $this->renderPartial('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionResetAllData()
    {
        // make all stock to 0
        $products = Product::find()->all();
        foreach ($products as $product) {
            $product->stock = 0;
            $product->save();

            // delete all items
            foreach ($product->items as $item) {
                // delete purchase_order_detail_item
                foreach ($item->purchaseOrderDetailItems as $purchaseOrderDetailItem) {
                    $purchaseOrderDetailItem->delete();
                }
                // delete stock_opname_detail_item
                foreach ($item->stockOpnameDetailItems as $stockOpnameDetailItem) {
                    $stockOpnameDetailItem->delete();
                }
                if (!$item->delete()) {
                    foreach ($item->errors as $error) return $error[0];
                }
            }

            // delete purchase_order_detail
            foreach ($product->purchaseOrderDetails as $purchaseOrderDetail) {
                if (!$purchaseOrderDetail->delete()) {
                    foreach ($purchaseOrderDetail->errors as $error) return $error[0];
                }
            }

            // delete all productions
            foreach ($product->productions as $production) {
                // delete production_detail
                foreach ($production->productionDetails as $productionDetail) {
                    if (!$productionDetail->delete()) {
                        foreach ($productionDetail->errors as $error) return $error[0];
                    }
                }
                // delete production_item
                foreach ($production->productionItems as $productionItem) {
                    if (!$productionItem->delete()) {
                        foreach ($productionItem->errors as $error) return $error[0];
                    }
                }
                if (!$production->delete()) {
                    foreach ($production->errors as $error) return $error[0];
                }
            }

            // delete rest production
            Production::deleteAll();
        }

        // delete item
        Item::deleteAll();

        // delete purchase_order
        PurchaseOrderDetailItem::deleteAll();
        PurchaseOrderDetail::deleteAll();
        PurchaseOrder::deleteAll();

        // delete purchase_order
        OrderDetailItem::deleteAll();
        OrderDetail::deleteAll();
        Order::deleteAll();

        // delete stock_opname
        StockOpnameDetailItem::deleteAll();
        StockOpnameDeletedItem::deleteAll();
        StockOpnameDetail::deleteAll();
        StockOpname::deleteAll();

        // reset setting
        $setting = Setting::find()->where(['key' => 'order-number'])->one();
        $setting->value = '0';
        $setting->save();
        $setting = Setting::find()->where(['key' => 'invoice-number'])->one();
        $setting->value = '0';
        $setting->save();
        $setting = Setting::find()->where(['key' => 'po-number'])->one();
        $setting->value = '0';
        $setting->save();
        $setting = Setting::find()->where(['key' => 'prod-number'])->one();
        $setting->value = '0';
        $setting->save();
        $setting = Setting::find()->where(['key' => 'so-number'])->one();
        $setting->value = '0';
        $setting->save();

        return 'WELL DONE';
    }
}
