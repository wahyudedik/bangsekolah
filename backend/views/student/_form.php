<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

use common\models\Year;
use common\models\SchoolClass;

?>
<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3>Form Siswa</h3>
                        <p>Masukkan data siswa</p>
                    </div> -->
                    <div class="card-body">

                        <?= $form->field($model, 'nis')->textInput() ?>

                        <?= $form->field($model, 'nisn')->textInput() ?>

                        <?= $form->field($model, 'name')->textInput() ?>
                        <?= $form->field($model, 'year')->dropDownList(Year::getList()) ?>
                        <?= $form->field($model, 'class')->dropDownList(SchoolClass::getList()) ?>

                        <div class="form-group">
                            <?php
                            if ($model->isNewRecord) {
                                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['student/index'], ['class' => 'btn btn-warning']);
                            } else {
                                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['student/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                            }
                            ?>
                            <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>
