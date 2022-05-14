<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
                    <div class="card-body">
                        <?= $form->field($model, 'year')->dropDownList(\common\models\Year::getList())->label('Tahun Ajaran') ?>

                        <?= $form->field($model, 'school_payment_id')->dropDownList(\common\models\SchoolPayment::getList(), [
                                'prompt' => '-- Select pembayaran --',
                                'onchange' => '
                                    $.get( "'.Url::toRoute('/invoice/list').'", { param: $(this).val() } )
                                        .done(function( data ) {
                                            $( "#'.Html::getInputId($model, 'jenis').'" ).val( data );
                                        }
                                    );
                                    '
                            ])->label('Nama Pembayaran');
                        ?>


                        <?= $form->field($model, 'jenis')->textInput() ?>

                        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

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
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <?= $form->field($model, 'year')->dropDownList(\common\models\Year::getList())->label('Tahun Ajaran') ?>

                        <?= $form->field($model, 'kelas')->dropDownList(\common\models\SchoolClass::getList())->label('Kelas') ?>

                        <?= $form->field($model, 'date')->textInput(['type' => 'date'])->label('Tanggal Awal') ?>

                        <?= $form->field($model, 'due_date')->textInput(['type' => 'date'])->label('Jatuh Tempo') ?>

                        <?= $form->field($model, 'amount')->textInput(['type' => 'number'])->label('Jumlah Tagihan') ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>