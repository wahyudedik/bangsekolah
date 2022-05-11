<?php

namespace backend\controllers;

use Yii;
use common\models\Supplier;
use common\models\PurchaseOrder;
use common\models\PurchaseOrderDetail;
use common\models\PurchaseOrderDetailItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class PurchaseOrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PurchaseOrder models.
     * @return mixed
     */
    public function actionIndex($q = null, $supplier = 'ALL', $startdate = null, $enddate = null, $status = 'ALL')
    {
        \common\helpers\Sidebar::setActiveMenu('purchase-order', 'index');
        
        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = PurchaseOrder::find()->joinWith(['supplier']);
        if ($q !== null) { 
            $query->andFilterWhere(['or', ['like', 'po_number', $q]]); 
        }
        if($status !== 'ALL') {
            $query->andFilterWhere(['purchase_order.status' => $status]);
        }
        if ($supplier !== 'ALL') { 
            $query->andFilterWhere(['or', ['like', 'supplier.name', $supplier]]); 
        }
        if ($startdate !== null) { 
            $query->andFilterWhere(['and', ['>=', 'DATE(purchase_order.created_at)', $startdate]]); 
        }
        if ($enddate !== null) { 
            $query->andFilterWhere(['and', ['<=', 'DATE(purchase_order.created_at)', $enddate]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        $suppliers = Supplier::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'suppliers' => $suppliers,
            'q' => $q,
            'supplier' => $supplier,
            'status' => $status,
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);
    }

    /**
     * Displays a single PurchaseOrder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detail_purchases = PurchaseOrderDetail::find()->where(['purchase_order_id' => $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detail_purchases' => $detail_purchases,
        ]);
    }

    /**
     * Creates a new PurchaseOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PurchaseOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PurchaseOrder model.
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
     * Deletes an existing PurchaseOrder model.
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
     * Finds the PurchaseOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($q = null, $supplier = 'ALL', $startdate = null, $enddate = null, $status = 'ALL')
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }
        $query = PurchaseOrder::find()->joinWith(['supplier']);
        if ($q !== null) { 
            $query->andFilterWhere(['or', ['like', 'po_number', $q]]); 
        }
        if($status !== 'ALL') {
            $query->andFilterWhere(['purchase_order.status' => $status]);
        }
        if ($supplier !== 'ALL') { 
            $query->andFilterWhere(['or', ['like', 'supplier.name', $supplier]]); 
        }
        if ($startdate !== null) { 
            $query->andFilterWhere(['and', ['>=', 'DATE(purchase_order.created_at)', $startdate]]); 
        }
        if ($enddate !== null) { 
            $query->andFilterWhere(['and', ['<=', 'DATE(purchase_order.created_at)', $enddate]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false,
        ]);

        $purchases = $dataProvider->models;

        // set title
        $sheet->setCellValue('A1', 'NUMBER');
        $sheet->setCellValue('B1', 'PO NUMBER');
        $sheet->setCellValue('C1', 'SUPPLIER NAME');
        $sheet->setCellValue('D1', 'TOTAL PRODUCT');
        $sheet->setCellValue('E1', 'TOTAL ITEM');
        $sheet->setCellValue('F1', 'TOTAL PRICE');
        $sheet->setCellValue('G1', 'STATUS');
        $sheet->setCellValue('H1', 'PURCHASED AT');
        $sheet->setCellValue('I1', 'PURCHASED BY');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getRowDimension('1')->setRowHeight(20);

        $column = 9;
        $row = 1;
        
        // styling
        $sheet->getStyleByColumnAndRow(0, $row, 8, $row)
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
        foreach ($purchases as $purchase) {
            $column = 9;
            $row++;

            // get total item
            $totalProduct = 0;
            $totalItem = 0;
            foreach ($purchase->purchaseOrderDetails as $detail) {
                $totalProduct++;
                $totalItem += count($detail->purchaseOrderDetailItems);
            }

            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('G', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('H', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('I', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($row - 1));
            $sheet->setCellValue('B' . $row, $purchase->po_number);
            $sheet->setCellValue('C' . $row, $purchase->supplier->name);
            $sheet->setCellValue('D' . $row, $totalProduct);
            $sheet->setCellValue('E' . $row, $totalItem);
            $sheet->setCellValue('F' . $row, $purchase->total);
            $sheet->setCellValue('G' . $row, $purchase->status);
            $sheet->setCellValue('H' . $row, $purchase->created_at ? date('d M Y H:i:s', strtotime($purchase->created_at)) : '');
            $sheet->setCellValue('I' . $row, $purchase->createdBy->name);
            $sheet->getStyleByColumnAndRow(0, $row, 8, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('eeeeee');
        }

        $filename = 'PURCHASE-ORDERS-' . date('YmdHis') . '.xlsx';
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
