<?php

namespace backend\controllers;

use Yii;
use common\models\Recipe;
use common\models\RecipeDetail;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * RecipeController implements the CRUD actions for Recipe model.
 */
class RecipeController extends Controller
{
    

    /**
     * Lists all Recipe models.
     * @return mixed
     */
    public function actionIndex($q = null)
    {
        \common\helpers\Sidebar::setActiveMenu('recipe', 'index');

        $query = Recipe::find()->where(['status' => 'ACTIVE'])->orderBy(['id' => SORT_DESC]);
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
        ]);
    }

    /**
     * Displays a single Recipe model.
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
     * Creates a new Recipe model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Recipe();
        $model->status = 'ACTIVE';

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/recipes/'.$temporary_image);
                $image->saveAs($path);
                Image::thumbnail($path, 700, 300)->save(Yii::getAlias('@uploads/recipes/large-'.$temporary_image), ['quality' => 80]);
                Image::thumbnail($path, 200, 200)->save(Yii::getAlias('@uploads/recipes/small-'.$temporary_image), ['quality' => 80]);
                $model->image = $temporary_image;
                @unlink($path);
            }
            $model->created_by = Yii::$app->user->identity->id;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Recipe model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldGambar = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (isset($image)) {
                $exploded_image = explode('.', $image->name);
                $extension = end($exploded_image);
                $temporary_image = Yii::$app->security->generateRandomString() . ".{$extension}";
                $path = Yii::getAlias('@uploads/recipes/'.$temporary_image);
                $image->saveAs($path);
                Image::thumbnail($path, 700, 300)->save(Yii::getAlias('@uploads/recipes/large-'.$temporary_image), ['quality' => 80]);
                Image::thumbnail($path, 200, 200)->save(Yii::getAlias('@uploads/recipes/small-'.$temporary_image), ['quality' => 80]);
                $model->image = $temporary_image;
                @unlink($path);

                @unlink(Yii::getAlias('@uploads/recipes/small-'.$oldGambar));
                @unlink(Yii::getAlias('@uploads/recipes/large-'.$oldGambar));
            } else {
                $model->image = $oldGambar;
            }
            $model->created_by = Yii::$app->user->identity->id;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                foreach ($model->getErrors() as $error) {
                    $message = $error[0];
                }
                Yii::$app->session->setFlash('danger', $message);
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        
    }

    /**
     * Deletes an existing Recipe model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->status = 'NONACTIVE';
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Recipe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recipe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recipe::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($q = null)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        $query = Recipe::find();
        if ($q !== null) { 
            $query->andFilterWhere(['or', ['like', 'name', $q]]); 
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false,
        ]);
        $recipes = $dataProvider->models;

        // set title
        $sheet->setCellValue('A1', 'NUMBER');
        $sheet->setCellValue('B1', 'RECIPE NAME');
        $sheet->setCellValue('C1', 'PRODUCT OUTPUT');
        $sheet->setCellValue('D1', 'EXPECTED QUANTITY');
        $sheet->setCellValue('E1', 'MATERIAL COUNT');
        $sheet->setCellValue('F1', 'RAW MATERIAL');
        $sheet->setCellValue('G1', 'CREATED BY');
        $sheet->setCellValue('H1', 'CREATED AT');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(50);
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
        foreach ($recipes as $recipe) {
            $column = 7;
            $row++;

            // get raw material string
            $materials = '';
            foreach ($recipe->recipeDetails as $detail) {
                $materials .= $detail->product->name.' ('.$detail->amount.' '.$detail->unit.'), ';
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
            $sheet->setCellValue('B' . $row, $recipe->name);
            $sheet->setCellValue('C' . $row, $recipe->product->name);
            $sheet->setCellValue('D' . $row, $recipe->expected_quantity);
            $sheet->setCellValue('E' . $row, count($recipe->recipeDetails));
            $sheet->setCellValue('F' . $row, $materials);
            $sheet->setCellValue('G' . $row, $recipe->createdBy->name);
            $sheet->setCellValue('H' . $row, $recipe->created_at);
            $sheet->getStyleByColumnAndRow(0, $row, 7, $row)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('eeeeee');
        }

        $filename = 'RECIPES-' . date('YmdHis') . '.xlsx';
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
