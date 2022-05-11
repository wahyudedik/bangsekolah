<?php

namespace backend\controllers;

use Yii;
use common\models\Outlet;
use common\models\Product;
use common\models\ProductOutlet;
use common\models\ProductCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductOutletController implements the CRUD actions for ProductOutlet model.
 */
class ProductOutletController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    /**
     * Lists all ProductOutlet models.
     * @return mixed
     */
    public function actionIndex($search = null, $category = null)
    {
        \common\helpers\Sidebar::setActiveMenu('product-outlet', 'index');

        if (!$category) {
            $category = ProductCategory::find()->orderBy(['id' => SORT_ASC])->limit(1)->one();
        }
        $category = ProductCategory::findOne($category);

        // membaca apakah ada nama variabel post
        $outlets = Outlet::find()->all();
        $products = Product::find()->where(['product_category_id' => $category->id])->all();

        if (isset($_POST['product_outlet'])) {
            foreach ($outlets as $outlet) {
                foreach ($products as $product) {
                    $productOutlet = ProductOutlet::find()->where(['outlet_id' => $outlet->id, 'product_id' => $product->id])->one();
                    if (isset($_POST['product_'.$outlet->id.'_'.$product->id])) {
                        if (!$productOutlet) {
                            $productOutlet = new ProductOutlet();
                        }
                        $productOutlet->outlet_id = $outlet->id;
                        $productOutlet->product_id = $product->id;
                        $productOutlet->save();
                    } else {
                        if ($productOutlet) {
                            $productOutlet->delete();    
                        }
                    }
                }
            }
            // Yii::$app->session->setFlash('success', 'Product availability saved successfully');
            return $this->render('index', [
                'search' => $search,
                'category' => $category,
            ]);
        } else {
            return $this->render('index', [
                'search' => $search,
                'category' => $category,
            ]);
        }
    }

    /**
     * Displays a single ProductOutlet model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductOutlet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductOutlet();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductOutlet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductOutlet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductOutlet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductOutlet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductOutlet::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
