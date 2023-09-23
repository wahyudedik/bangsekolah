<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = 'Import Guru';
$this->params['breadcrumbs'][] = ['label' => 'Guru', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="teacher-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <!-- <div class="card-header">
                    <h3>Import Data Guru</h3>
                    <p>Untuk input data banyak teacher/</p>
                </div> -->
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5>Panduan import</h5>
                        <p>Daftar guru selain diinput secara manual, bisa juga diimport dari dokumen Excel. Beberapa hal yang harus diperhatikan ketika melakukan import transaksi adalah memastikan bahwa master kelas telah dibuat terlebih dahulu.
                        Berikut adalah tampilan template excel untuk import data guru:</p>
                        </p>
                        <p>
                            <img src="<?= $baseUrl ?>/images/template-preview-teacher.png" width="100%">
                        </p>
                        <p>Download template import data guru di sini: <br><a href="<?= $baseUrl ?>/template/template-import-guru.xlsx" download="template-import-guru.xlsx" style="color: #007bff;">TEMPLATE IMPORT GURU.xlsx</a></p>
                    </div>

                    <?= $form->field($model, 'file')->fileInput() ?>
                    <div class="form-group">
                        <?php
                        if ($model->isNewRecord) {
                            echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['teacher/index'], ['class' => 'btn btn-warning']);
                        } else {
                            echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['teacher/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
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
