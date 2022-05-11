<?php

namespace backend\controllers;

use Yii;
use common\models\Production;
use common\models\ProductionItem;
use common\models\Item;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductionController implements the CRUD actions for Production model.
 */
class ProductionController extends Controller
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
     * Lists all Production models.
     * @return mixed
     */
    public function actionIndex($q = null, $startdate = null, $enddate = null, $status = 'ALL')
    {
        \common\helpers\Sidebar::setActiveMenu('production', 'index');
        
        if($startdate == null && $enddate == null){
            $startdate = date('Y-m-d', strtotime('-1 month'));
            $enddate = date('Y-m-d');
        }

        $query = Production::find()->joinWith(['recipe']);
        if($status !== 'ALL') {
            $query->andFilterWhere(['production.status' => $status]);
        }
        if ($q !== null) {
            $query->andFilterWhere(['or', ['like', 'recipe.name', $q]]);
        }
        if($startdate !== null){
            $query->andFilterWhere(['and', ['>=', 'DATE(production.created_at)', $startdate]]);
                
        }
        if($enddate !== null){
            $query->andFilterWhere(['and', ['<=', 'DATE(production.created_at)', $enddate]]);
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
     * Displays a single Production model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $items = Item::find()->where(['production_id' => $id])->all();
        $ingredients = ProductionItem::find()->where(['production_id' => $id])->orderBy(['id' => SORT_DESC])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $items,
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Creates a new Production model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Production();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Production model.
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
     * Deletes an existing Production model.
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
     * Finds the Production model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Production the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Production::findOne($id)) !== null) {
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
        $query = Production::find()->joinWith(['recipe']);
        if($status !== 'ALL') {
            $query->andFilterWhere(['production.status' => $status]);
        }
        if ($q !== null) {
            $query->andFilterWhere(['or', ['like', 'recipe.name', $q]]);
        }
        if($startdate !== null){
            $query->andFilterWhere(['and', ['>=', 'DATE(production.created_at)', $startdate]]);
                
        }
        if($enddate !== null){
            $query->andFilterWhere(['and', ['<=', 'DATE(production.created_at)', $enddate]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false,
        ]);

        $productions = $dataProvider->models;

        // set title
        $sheet->setCellValue('A1', 'NUMBER');
        $sheet->setCellValue('B1', 'PROD NUMBER');
        $sheet->setCellValue('C1', 'RECIPE NAME');
        $sheet->setCellValue('D1', 'PRODUCT OUTPUT');
        $sheet->setCellValue('E1', 'OUTPUT QTY');
        $sheet->setCellValue('F1', 'PRODUCTION COST');
        $sheet->setCellValue('G1', 'STATUS');
        $sheet->setCellValue('H1', 'PRODUCED AT');
        $sheet->setCellValue('I1', 'PRODUCED BY');
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
        foreach ($productions as $production) {
            $column = 9;
            $row++;

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
            $sheet->setCellValue('B' . $row, $production->production_number);
            $sheet->setCellValue('C' . $row, $production->recipe->name);
            $sheet->setCellValue('D' . $row, $production->recipe->product->name);
            $sheet->setCellValue('E' . $row, $production->quantity.' '.$production->recipe->product->unit);
            $sheet->setCellValue('F' . $row, $production->productionCost);
            $sheet->setCellValue('G' . $row, $production->status);
            $sheet->setCellValue('H' . $row, $production->created_at ? date('d M Y H:i:s', strtotime($production->created_at)) : '');
            $sheet->setCellValue('I' . $row, $production->createdBy->name);
            $sheet->getStyleByColumnAndRow(0, $row, 8, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('eeeeee');
        }

        $filename = 'PRODUCTIONS-' . date('YmdHis') . '.xlsx';
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
