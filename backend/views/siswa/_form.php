<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'nis')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'nisn')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Nama Siswa') ?>

                <?= $form->field($model, 'class')->textInput(['maxlength' => true])->label('Kelas') ?>

                <?= $form->field($model, 'year')->textInput(['maxlength' => true])->label('Tahun') ?>

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
    </div>
</div>
