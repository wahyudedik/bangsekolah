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
                    <div class="card-header">
                        <h5>Nama Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <?= $form->field($model, 'school_payment_id')->dropDownList(\common\models\SchoolPayment::getList(), [
                            'prompt' => '-- Pilih pembayaran --',
                        ])->label('Nama Pembayaran');
                        ?>
                        <?= $form->field($model, 'payment_type')->hiddenInput()->label(false) ?>
                        <div class="callout callout-info" id="payment-type-div" style="display: none;">
                            <h5 id="payment-type-name"></h5>
                            <ul>
                                <li>MONTHLY: pembayaran bulanan</li>
                                <li>ONE TIME PAYMENT: satu kali pembayaran (bisa dicicil)</li>
                                <li>VOLUNTARILY: iuran sukarela</li>
                            </ul>
                        </div>
                        <div id="invoice-date" style="display: none;">
                            <?= $form->field($model, 'date')->textInput(['type' => 'date', 'value' => date('Y-m-d')])->label('Tanggal Awal') ?>
                            <?= $form->field($model, 'due_date')->textInput(['type' => 'date', 'value' => date('Y-m-t')])->label('Jatuh Tempo') ?>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Diberikan Kepada</h5>
                    </div>
                    <div class="card-body">
                        <?= $form->field($model, 'year')->dropDownList(\common\models\Year::getList())->label('Tahun Ajaran') ?>
                        <?= $form->field($model, 'class')->dropDownList(\common\models\SchoolClass::getList(), [
                            'prompt' => '-- Pilih kelas --',
                        ])->label('Kelas') ?>
                        <div class="callout callout-info" id="student-class-div" style="display: none;">
                            <p id="student-class-count"></p>
                        </div>
                        <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'value' => '0'])->label('Nominal Tagihan') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php
            if ($model->isNewRecord) {
                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/index'], ['class' => 'btn btn-warning']);
            } else {
                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
            }
            ?>
            <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['id' => 'btn-submit', 'class' => 'btn btn-success', 'style' => 'display:none;', 'onclick' => 'return confirm("Yakin data di atas sudah benar?");']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">

    function checkValidation() {
        if (
            $('#invoice-school_payment_id option:selected').val() !== '' &&
            $('#invoice-class option:selected').val() !== '' &&
            $('#invoice-amount').val() > 0
        ) {
            $('#btn-submit').show();
        } else {
            $('#btn-submit').hide();
        }
    }

    $('#invoice-school_payment_id').change(function(){
        $.get('<?= Url::to(['invoice/get-payment-type']) ?>', { payment: $(this).val() })
            .done(function(data) {
                $('#invoice-payment_type').val(data);
                if (data) {
                    $('#payment-type-div').show();
                    $('#payment-type-name').html(data);
                } else {
                    $('#payment-type-div').hide();
                }

                if (data == 'MONTHLY') {
                    $('#invoice-date').hide();
                } else {
                    $('#invoice-date').show();
                }
            }
        );
        checkValidation();
    });

    $('#invoice-year').change(function(){
        $("#invoice-class").val($("#invoice-class option:first").val());
        $("#invoice-class").trigger('change');
    });

    $('#invoice-class').change(function(){
        $.get('<?= Url::to(['invoice/get-student-class']) ?>', { year: $('#invoice-year').val(), class: $('#invoice-class').val() })
            .done(function(data) {
                if (data > 0) {
                    $('#student-class-div').show();
                    $("#student-class-div").attr('class', 'callout callout-info');
                    $('#student-class-count').html('Total siswa yang akan mendapatkan tagihan ini:<br><h5 class="text-primary">'+data+' SISWA</h5>');
                } else if (data == 0) {
                    $('#student-class-div').show();
                    $("#student-class-div").attr('class', 'callout callout-danger');
                    $('#student-class-count').html('Tidak ada siswa di kelas ini. Tagihan tidak bisa dibuat.');
                } else {
                    $('#student-class-div').hide();
                }
            }
        );
        checkValidation();
    });

    $('#invoice-amount').change(function(){
        checkValidation();
    });

    $('#invoice-amount').keyup(function(){
        checkValidation();
    });

</script>