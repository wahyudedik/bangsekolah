<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
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

                        <?= $form->field($model, 'student_id')->textInput() ?>

                        <?= $form->field($model, 'school_payment_id')->textInput() ?>

                        <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'month')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'amount')->textInput() ?>

                        <?= $form->field($model, 'due_date')->textInput() ?>

                        <div class="form-group">
                            <?php
                            if ($model->isNewRecord) {
                                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/index'], ['class' => 'btn btn-warning']);
                            } else {
                                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
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