<?php

namespace backend\controllers;

use Yii;
use common\models\Item;
use common\models\StockOpname;
use common\models\StockOpnameDetail;
use common\models\StockOpnameDetailItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * StockOpnameController implements the CRUD actions for Item model.
 */
class StockOpnameController extends Controller
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
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex($q = null, $startdate = null, $enddate = null, $status = 'ALL')
    {
        \common\helpers\Sidebar::setActiveMenu('stock-opname', 'index');
        
        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = StockOpname::find();
        if ($q !== null) { 
            $query->andFilterWhere(['or', ['like', 'so_number', $q]]); 
        }
        if($status !== 'ALL') {
            $query->andFilterWhere(['stock_opname.status' => $status]);
        }
        if ($startdate !== null) { 
            $query->andFilterWhere(['and', ['>=', 'DATE(stock_opname.created_at)', $startdate]]); 
        }
        if ($enddate !== null) { 
            $query->andFilterWhere(['and', ['<=', 'DATE(stock_opname.created_at)', $enddate]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'q' => $q,
            'status' => $status,
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detail_opnames = StockOpnameDetail::find()->where(['stock_opname_id' => $id])->all();
        //$pages = new Pagination(['totalCount' => $countQuery->count()]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detail_opnames' => $detail_opnames,
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Item model.
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
     * Deletes an existing Item model.
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
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockOpname::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($q = null, $startdate = null, $enddate = null, $status = 'ALL')
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = StockOpname::find();
        if ($q !== null) { 
            $query->andFilterWhere(['or', ['like', 'so_number', $q]]); 
        }
        if($status !== 'ALL') {
            $query->andFilterWhere(['stock_opname.status' => $status]);
        }
        if ($startdate !== null) { 
            $query->andFilterWhere(['and', ['>=', 'DATE(stock_opname.created_at)', $startdate]]); 
        }
        if ($enddate !== null) { 
            $query->andFilterWhere(['and', ['<=', 'DATE(stock_opname.created_at)', $enddate]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false,
        ]);

        $opnames = $dataProvider->models;

        // set title
        $sheet->setCellValue('A1', 'NUMBER');
        $sheet->setCellValue('B1', 'SO NUMBER');
        $sheet->setCellValue('C1', 'TOTAL PRODUCT');
        $sheet->setCellValue('D1', 'SCANNED ITEM');
        $sheet->setCellValue('E1', 'DELETED ITEM');
        $sheet->setCellValue('F1', 'STATUS');
        $sheet->setCellValue('G1', 'CHECKED AT');
        $sheet->setCellValue('H1', 'CHECKED BY');
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
        foreach ($opnames as $opname) {
            $column = 7;
            $row++;

            // get total item
            $totalProduct = 0;
            $totalItem = 0;
            $deletedItem = 0;
            foreach ($opname->stockOpnameDetails as $detail) {
                $totalProduct++;
                $totalItem += count($detail->stockOpnameDetailItems);
                $deletedItem += count($detail->stockOpnameDeletedItems);
            }

            $sheet->getStyle('A', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('B', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('C', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('D', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('E', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('F', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('G', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getStyle('H', $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->setCellValue('A' . $row, ($row - 1));
            $sheet->setCellValue('B' . $row, $opname->so_number);
            $sheet->setCellValue('C' . $row, $totalProduct);
            $sheet->setCellValue('D' . $row, $totalItem);
            $sheet->setCellValue('E' . $row, $deletedItem);
            $sheet->setCellValue('F' . $row, $opname->status);
            $sheet->setCellValue('G' . $row, $opname->created_at ? date('d M Y H:i:s', strtotime($opname->created_at)) : '');
            $sheet->setCellValue('H' . $row, $opname->createdBy->name);
            $sheet->getStyleByColumnAndRow(0, $row, 7, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('eeeeee');
        }

        $filename = 'STOCK-OPNAMES-' . date('YmdHis') . '.xlsx';
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
