<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Student */

$this->title = 'Import Siswa';
$this->params['breadcrumbs'][] = ['label' => 'Siswa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="student-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <!-- <div class="card-header">
                    <h3>Import Data Siswa</h3>
                    <p>Untuk input data banyak student/</p>
                </div> -->
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5>Panduan import</h5>
                        <p>Daftar siswa selain diinput secara manual, bisa juga diimport dari dokumen Excel. Beberapa hal yang harus diperhatikan ketika melakukan import transaksi adalah memastikan bahwa master kelas telah dibuat terlebih dahulu.
                        Berikut adalah tampilan template excel untuk import data siswa:</p>
                        </p>
                        <p>
                            <img src="<?= $baseUrl ?>/images/template-preview-student.png" width="100%">
                        </p>
                        <p>Download template import data siswa di sini: <br><a href="<?= $baseUrl ?>/template/template-import-siswa.xlsx" download="template-import-siswa.xlsx" style="color: #007bff;">TEMPLATE IMPORT SISWA.xlsx</a></p>
                    </div>

                    <?= $form->field($model, 'file')->fileInput() ?>
                    <div class="form-group">
                        <?php
                        if ($model->isNewRecord) {
                            echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['student/index'], ['class' => 'btn btn-warning']);
                        } else {
                            echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['student/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                        }
                        ?>
                        <?= Html::submitButton('<i class="fas fa-save"></i> Import', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
