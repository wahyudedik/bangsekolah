<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\ProductCategory;
use common\models\Production;
use common\models\ProductionDetail;
use common\models\ProductLog;
use common\models\Item;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex($q = null, $category = 'ALL')
    {
        \common\helpers\Sidebar::setActiveMenu('product', 'index');

        if ($category == 'ALL') {
            // $category = ProductCategory::find()->orderBy(['id' => SORT_ASC])->limit(1)->one();
            $category = null;
            $query = Product::find()
                ->where(['status' => 'ACTIVE'])
                ->orderBy(['id' => SORT_DESC]);
        } else {
            $category = ProductCategory::findOne($category);
            $query = Product::find()
                ->where(['product_category_id' => $category->id])
                ->andWhere(['status' => 'ACTIVE'])
                ->orderBy(['id' => SORT_DESC]);
        }

        if ($q !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'name', $q],
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
            'category' => $category,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $items = Item::find()->where(['product_code' => $model->code])->andWhere(['status' => 'ACTIVE'])->orderBy(['id' => SORT_DESC])->all();
        $productlogs = ProductLog::find()->where(['product_id' => $model->id])->all();
        $productions = Production::find()->where(['product_id' => $model->id, 'status' => 'FINISHED'])->all();
        $usages = ProductionDetail::find()->where(['product_id' => $model->id])->all();
        return $this->render('view', [
            'model' => $model,
            'items' => $items,
            'productlogs' => $productlogs,
            'productions' => $productions,
            'usages' => $usages,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->stock = 0;
        $model->price_sale = 0;
        $model->sale_status = 'TRUE';
        $model->created_by = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->code = trim(strtoupper(str_replace(' ', '-', $model->code)));
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $produk_log = new ProductLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $produk_log->product_id = $model->id;
            $produk_log->code = $model->code;
            $produk_log->name = $model->name;
            $produk_log->description = $model->description;
            $produk_log->stock = $model->stock;
            $produk_log->price_sale = $model->price_sale;
            $produk_log->product_category_id = $model->product_category_id;
            $produk_log->created_by = Yii::$app->user->identity->id;
            $produk_log->updated_by = Yii::$app->user->identity->id;

            if ($produk_log->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($produk_log->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['update', 'id' => $model->id]);
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 'NONACTIVE';
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($q = null, $category = 'ALL')
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        $query = Product::find();
        if ($q !== null) { 
            $query->andFilterWhere(['or', ['like', 'name', $q]]); 
        }
        if($category !== 'ALL') {
            $query->andFilterWhere(['category_id' => $category]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false,
        ]);
        $products = $dataProvider->models;

        // set title
        $sheet->setCellValue('A1', 'NUMBER');
        $sheet->setCellValue('B1', 'CODE');
        $sheet->setCellValue('C1', 'PRODUCT NAME');
        $sheet->setCellValue('D1', 'CATEGORY');
        $sheet->setCellValue('E1', 'STOCK');
        $sheet->setCellValue('F1', 'UNIT');
        $sheet->setCellValue('G1', 'PRICE SALE');
        $sheet->setCellValue('H1', 'LAST OPNAMED AT');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getRowDimension('1')->setRowHeight(20);

        $column = 8;
        $row = 1;
        
        // styling
        $sheet->getStyleByColumnAndRow(0, $row, 7, $row)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('d32f2f');
        $arrStyle = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ]
        ];
        $sheet->getStyleByColumnAndRow(0, $row, ($column-1), $row)->applyFromArray($arrStyle);

        // populate records data
        $row = 1;
        foreach ($products as $product) {
            $column = 7;
            $row++;

            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('G', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('H', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($row - 1));
            $sheet->setCellValue('B' . $row, $product->code);
            $sheet->setCellValue('C' . $row, $product->name);
            $sheet->setCellValue('D' . $row, $product->productCategory->name);
            $sheet->setCellValue('E' . $row, $product->stock);
            $sheet->setCellValue('F' . $row, $product->unit);
            $sheet->setCellValue('G' . $row, $product->price_sale);
            $sheet->setCellValue('H' . $row, $product->lastOpnameAt);
            $sheet->getStyleByColumnAndRow(0, $row, 7, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('eeeeee');
        }

        $filename = 'PRODUCTS-' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        unset($objPHPExcel);
        // most important code (exit)
        exit;
    }
}
