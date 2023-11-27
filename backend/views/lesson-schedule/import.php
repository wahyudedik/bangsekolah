<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = 'Import Presensi';
$this->params['breadcrumbs'][] = ['label' => 'Presensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="teacher-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5>Panduan import</h5>
                        <p>Daftar presensi selain diinput secara manual, bisa juga diimport dari dokumen Excel. Beberapa hal yang harus diperhatikan ketika melakukan import transaksi adalah import hanya bisa dilakukan oleh guru bersangkutan dan siswa belum pernah diabsen manual.
                            Berikut adalah tampilan template excel untuk import data presensi:</p>
                        </p>
                        <p>
                            <img src="<?= $baseUrl ?>/images/template-preview-presensi.png" width="100%">
                        </p>
                        <p>Download template import data presensi di sini: <br><a href="<?= $baseUrl ?>/template/template-import-presensi.xlsx" download="template-import-presensi.xlsx" style="color: #007bff;">TEMPLATE IMPORT PresensiNSI.xlsx</a></p>
                    </div>

                    <?= $form->field($model, 'file')->fileInput() ?>
                    <div class="form-group">
                        <?php
                        if ($model->isNewRecord) {
                            echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['lesson-schedule/index'], ['class' => 'btn btn-warning']);
                        } else {
                            echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['lesson-schedule/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
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