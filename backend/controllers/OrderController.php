<?php

namespace backend\controllers;

use Yii;
use common\models\Outlet;
use common\models\Order;
use common\models\OrderDetail;
use common\models\OrderDetailItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex($q = null, $outlet = 'ALL', $startdate = null, $enddate = null, $status = 'ALL')
    {
        \common\helpers\Sidebar::setActiveMenu('order', 'index');
        
        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = Order::find()->joinWith(['outlet']);
        if($q !== null) {
            $query->andFilterWhere(['order_number' => $q]);
        }
        if($status !== 'ALL') {
            $query->andFilterWhere(['order.status' => $status]);
        }
        if ($outlet !== 'ALL') {
            $query->andFilterWhere(['or', ['like', 'outlet.name', $outlet]]);
        }
        if ($startdate !== null) { 
            $query->andFilterWhere(['and', ['>=', 'DATE(order.created_at)', $startdate]]); 
        }
        if ($enddate !== null) { 
            $query->andFilterWhere(['and', ['<=', 'DATE(order.created_at)', $enddate]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        $outlets = Outlet::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'outlets' => $outlets,
            'q' => $q,
            'outlet' => $outlet,
            'status' => $status,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detail_orders = OrderDetail::find()->where(['order_id' => $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detail_orders' => $detail_orders,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
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
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($q = null, $outlet = 'ALL', $startdate = null, $enddate = null, $status = 'ALL')
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // populate data
        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }
        $query = Order::find()->joinWith(['outlet']);
        if($q !== null) {
            $query->andFilterWhere(['order_number' => $q]);
        }
        if($status !== 'ALL') {
            $query->andFilterWhere(['order.status' => $status]);
        }
        if ($outlet !== 'ALL') {
            $query->andFilterWhere(['or', ['like', 'outlet.name', $outlet]]);
        }
        if ($startdate !== null) { 
            $query->andFilterWhere(['and', ['>=', 'DATE(order.created_at)', $startdate]]); 
        }
        if ($enddate !== null) { 
            $query->andFilterWhere(['and', ['<=', 'DATE(order.created_at)', $enddate]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false,
        ]);

        $orders = $dataProvider->models;

        // set title
        $sheet->setCellValue('A1', 'NUMBER');
        $sheet->setCellValue('B1', 'ORDER NUMBER');
        $sheet->setCellValue('C1', 'INVOICE NUMBER');
        $sheet->setCellValue('D1', 'OUTLET');
        $sheet->setCellValue('E1', 'TOTAL PRODUCT');
        $sheet->setCellValue('F1', 'TOTAL ITEM');
        $sheet->setCellValue('G1', 'TOTAL PRICE');
        $sheet->setCellValue('H1', 'DELIVERY COST');
        $sheet->setCellValue('I1', 'TAX');
        $sheet->setCellValue('J1', 'TAX DESC');
        $sheet->setCellValue('K1', 'STATUS');
        $sheet->setCellValue('L1', 'ORDERED AT');
        $sheet->setCellValue('M1', 'FINALIZED AT');
        $sheet->setCellValue('N1', 'PAID AT');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getRowDimension('1')->setRowHeight(20);

        $column = 14;
        $row = 1;
        
        // styling
        $sheet->getStyleByColumnAndRow(0, $row, 13, $row)
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
        foreach ($orders as $order) {
            $column = 14;
            $row++;

            // get total item
            $totalProduct = 0;
            $totalItem = 0;
            foreach ($order->orderDetails as $detail) {
                $totalProduct++;
                $totalItem += count($detail->orderDetailItems);
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
            $sheet->getStyle('J', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('K', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('L', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('M', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('N', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($row - 1));
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, $order->invoice_number);
            $sheet->setCellValue('D' . $row, $order->outlet->name);
            $sheet->setCellValue('E' . $row, $totalProduct);
            $sheet->setCellValue('F' . $row, $totalItem);
            $sheet->setCellValue('G' . $row, $order->total);
            $sheet->setCellValue('H' . $row, $order->shipping_cost);
            $sheet->setCellValue('I' . $row, $order->tax);
            $sheet->setCellValue('J' . $row, $order->tax_description);
            $sheet->setCellValue('K' . $row, $order->status);
            $sheet->setCellValue('L' . $row, $order->ordered_at ? date('d M Y H:i:s', strtotime($order->ordered_at)) : '');
            $sheet->setCellValue('M' . $row, $order->finalized_at ? date('d M Y H:i:s', strtotime($order->finalized_at)) : '');
            $sheet->setCellValue('N' . $row, $order->paid_at ? date('d M Y H:i:s', strtotime($order->paid_at)) : '');
            $sheet->getStyleByColumnAndRow(0, $row, 13, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('eeeeee');
        }

        $filename = 'ORDERS-' . date('YmdHis') . '.xlsx';
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
