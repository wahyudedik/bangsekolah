<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $model common\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Form Siswa</h3>
                        <p>Untuk input data satu siswa</p>
                    </div>
                    <div class="card-body">

                        <?= $form->field($model, 'nis')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'nisn')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Nama Siswa') ?>

                        <?= $form->field($model, 'class')->textInput(['maxlength' => true])->label('Kelas') ?>

                        <?= $form->field($model, 'year')->textInput(['maxlength' => true])->label('Tahun') ?>

                    </div>
                </div>
            </div>
            <?php if ($model->isNewRecord) { ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3>Import Data Siswa</h3>
                            <p>Untuk input data banyak siswa</p>
                        </div>
                        <div class="card-body">
                            <?= $form->field($model, 'file')->fileInput()->label('Import Data Siswa') ?>
                            
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="form-group">
            <?php
            if ($model->isNewRecord) {
                echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['siswa/index'], ['class' => 'btn btn-warning']);
            } else {
                echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['siswa/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
            }
            ?>
            <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
