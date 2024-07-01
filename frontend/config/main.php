<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

use \yii\web\Request;
$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'vistaeducation-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'urlManager' => [
            'baseUrl' => $baseUrl, //baseUrl tambahan maf
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                'privacy' => 'site/privacy',
                'post' => 'post/index',
                'school-register' => 'school-register/create',

                /*
                 *---------------------------------------
                 * API APP PRODUKSI
                 *---------------------------------------
                 *
                 */
                // 'api/login' => 'api-login/login',
                // 'api/product' => 'api-product/index',
                // 'api/product/category' => 'api-product/category',
                // 'api/product/item' => 'api-product/items',
                // 'api/product/<id>' => 'api-product/view',
                // 'api/product/create' => 'api-product/create',
                // 'api/product/update/<id>' => 'api-product/update',
                // 'api/product/delete/<id>' => 'api-product/delete',
                // 'api/product/<code>/item' => 'api-product/item-view',
                // 'api/product/<code>/item/usages' => 'api-product/item-usages',

                // 'api/production' => 'api-production/index',
                // 'api/production/item' => 'api-production/items',
                // 'api/production/create' => 'api-production/create',
                // 'api/production/scan-item' => 'api-production/scan-item',
                // 'api/production/item/add' => 'api-production/add-item',
                // 'api/production/item/<id>/delete' => 'api-production/delete-item',
                // 'api/production/<id>' => 'api-production/view',
                // 'api/production/<id>/delete' => 'api-production/delete',
                // 'api/production/<id>/ingredients' => 'api-production/ingredients',
                // 'api/production/<id>/update' => 'api-production/update',
                // 'api/production/<id>/finish' => 'api-production/finish',

                // 'api/recipe' => 'api-recipe/index',
                // 'api/recipe/<id>' => 'api-recipe/view',
                // 'api/recipe/<id>/ingredients' => 'api-recipe/ingredients',
                // 'api/recipe/create' => 'api-recipe/create',
                // 'api/recipe/update/<id>' => 'api-recipe/update',
                // 'api/recipe/delete/<id>' => 'api-recipe/delete',

                // 'api/order' => 'api-order/index',
                // 'api/order/product-order' => 'api-order/product-order',
                // 'api/order/scan-item' => 'api-order/scan-item',
                // 'api/order/update-finalize' => 'api-order/update-finalize',
                // 'api/order/update-shipping' => 'api-order/update-shipping',
                // 'api/order/update-payment' => 'api-order/update-payment',
                // 'api/order/detail/create' => 'api-order/create-detail',
                // 'api/order/<id>' => 'api-order/view',
                // 'api/order/<id>/update' => 'api-order/update',
                // 'api/order/<id>/update-quantity' => 'api-order/update-quantity',
                // 'api/order/<id>/order-view' => 'api-order/order-view',
                // 'api/order/<id>/note-update' => 'api-order/note-update',
                // 'api/order/detail/<id>/delete' => 'api-order/delete-detail',
                // 'api/order/item/add' => 'api-order/add-item',
                // 'api/order/item/<id>/delete' => 'api-order/delete-item',

                // 'api/purchase' => 'api-purchase/index',
                // 'api/purchase/create' => 'api-purchase/create',
                // 'api/purchase/purchase-detail' => 'api-purchase/purchase-detail',
                // 'api/purchase/product-purchase' => 'api-purchase/product-purchase',
                // 'api/purchase/product-purchase/add' => 'api-purchase/add-product',
                // 'api/purchase/product-purchase/<id>/update' => 'api-purchase/purchase-update',
                // 'api/purchase/purchase-item' => 'api-purchase/purchase-item',
                // 'api/purchase/<id>' => 'api-purchase/view',
                // 'api/purchase/<id>/delete' => 'api-purchase/delete',
                // 'api/purchase/<id>/finish' => 'api-purchase/finish',
                // 'api/purchase/<id>/update-supplier' => 'api-purchase/update-supplier',
                // 'api/purchase/<id>/note-update' => 'api-purchase/note-update',
                // 'api/purchase/<id>/purchase-detail' => 'api-purchase/purchase-detailview',

                // 'api/opname' => 'api-opname/index',
                // 'api/opname/create' => 'api-opname/create',
                // 'api/opname/list-item' => 'api-opname/list-item',
                // 'api/opname/list-deleted' => 'api-opname/list-deleted',
                // 'api/opname/product-opname' => 'api-opname/product-opname',
                // 'api/opname/find-item' => 'api-opname/find-item',
                // 'api/opname/detail/create' => 'api-opname/create-detail',
                // 'api/opname/item/create' => 'api-opname/create-item',
                // 'api/opname/item/scan-item' => 'api-opname/scan-item',
                // 'api/opname/item/<id>/delete' => 'api-opname/delete-item',
                // 'api/opname/<id>' => 'api-opname/view',
                // 'api/opname/<id>/delete' => 'api-opname/delete',
                // 'api/opname/<id>/update' => 'api-opname/update',
                // 'api/opname/<id>/update-process' => 'api-opname/update-process',
                // 'api/opname/<id>/opname-detail' => 'api-opname/opname-detail',
                // 'api/opname/detail/<id>/update-detail' => 'api-opname/update-detail',

                // 'api/home/order' => 'api-home/order',
                // 'api/home/summary' => 'api-home/summary',
                // 'api/supplier' => 'api-supplier/supplier',
                // 'api/user/<id>' => 'api-user/detail',
                // 'api/user/update/<id>' => 'api-user/update',
                // 'api/webview/chart' => 'api-webview/chart',
                
                /*
                 *---------------------------------------
                 * API APP MITRA / OUTLET
                 *---------------------------------------
                 *
                 */
                'api/outlet/login' => 'api-outlet-login/login',
                'api/outlet/product' => 'api-outlet-product/index',
                'api/outlet/product/category' => 'api-outlet-product/category',
                'api/outlet/product/<id>' => 'api-outlet-product/view',
                'api/outlet/cart/add' => 'api-outlet-cart/add',
                'api/outlet/cart/update' => 'api-outlet-cart/update',
                'api/outlet/cart/update-note' => 'api-outlet-cart/update-note',
                'api/outlet/cart/view-note' => 'api-outlet-cart/view-note',
                'api/outlet/cart/delete' => 'api-outlet-cart/delete',
                'api/outlet/cart/checkout' => 'api-outlet-cart/checkout',
                'api/outlet/cart/<id>' => 'api-outlet-cart/detail',
                'api/outlet/cart' => 'api-outlet-cart/index',
                'api/outlet/order/<id>' => 'api-outlet-order/view',
                'api/outlet/order/<id>/order-detail' => 'api-outlet-order/order-detail',
                'api/outlet/order/<order_detail_id>/order-detail-item' => 'api-outlet-order/order-detail-item',
                'api/outlet/order/item/verify' => 'api-outlet-order/item-verify',
                'api/outlet/order' => 'api-outlet-order/index',
                'api/outlet/order/update-note' => 'api-outlet-order/update-note',
                'api/outlet/profile' => 'api-outlet-profile/index',
                'api/outlet/webview/chart' => 'api-outlet-webview/chart',
                'api/outlet/webview/invoice/<number>' => 'api-outlet-webview/invoice',
                'api/outlet/webview/invoice/<number>/download' => 'api-outlet-webview/download-invoice',
                'api/outlet/home/summary' => 'api-outlet-home/summary',

                '<controller>' => '<controller>/index',
                '<controller>/<action>' => '<controller>/<action>',

            ],
        ],
        //request tambahan maf
        'request' => [
            'baseUrl' => $baseUrl,
        ],
    ],
    'params' => $params,
];
