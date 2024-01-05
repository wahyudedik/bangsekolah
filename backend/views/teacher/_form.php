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
                        <h3>Form Guru</h3>
                        <p>Masukkan data guru</p>
                    </div> -->
                    <div class="card-body">

                        <?= $form->field($model, 'nik')->textInput() ?>

                        <?= $form->field($model, 'name')->textInput() ?>
                        <?= $form->field($model, 'finger_code')->textInput() ?>
                        <?= $form->field($model, 'gender')->dropDownList(
                            ['L' => 'Laki - Laki', 'P' => 'Perempuan']
                        ) ?>
                        <?= $form->field($model, 'year')->dropDownList(Year::getList()) ?>
                        <?= $form->field($model, 'class')->dropDownList(SchoolClass::getList(), [
                            'prompt' => 'Pilih kelas',
                        ]) ?>

                        <div class="form-group">
                            <?php
                            if ($model->isNewRecord) {
                                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['teacher/index'], ['class' => 'btn btn-warning']);
                            } else {
                                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['teacher/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
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