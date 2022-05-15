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
                        <?= $form->field($model, 'year')->dropDownList(\common\models\Year::getList())->label('Tahun Ajaran') ?>
                        <?= $form->field($model, 'school_payment_id')->dropDownList(\common\models\SchoolPayment::getList(), [
                                'prompt' => '-- Pilih pembayaran --',
                            ])->label('Nama Pembayaran');
                        ?>
                        <?= $form->field($model, 'payment_type')->hiddenInput()->label(false) ?>
                        <div class="callout callout-info" id="payment-type-info" style="display: none;">
                            <h5 id="payment-type-name"></h5>
                            <p>MONTHLY adalah blablabla. ONE TIME PAYMENT adalah blablabla. VOLUNTARY adalah blablabla</p>
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
                        <?= $form->field($model, 'class')->dropDownList(\common\models\SchoolClass::getList())->label('Kelas') ?>
                        <div id="invoice-date" style="display: none;">
                            <?= $form->field($model, 'date')->textInput(['type' => 'date', 'value' => date('Y-m-d')])->label('Tanggal Awal') ?>
                            <?= $form->field($model, 'due_date')->textInput(['type' => 'date', 'value' => date('Y-m-t')])->label('Jatuh Tempo') ?>
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
            <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    $('#invoice-school_payment_id').change(function(){
        $.get('<?= Url::to(['invoice/get-payment-type']) ?>', { payment: $(this).val() })
            .done(function(data) {
                $('#invoice-payment_type').val(data);
                // alert(data);
                if (data) {
                    $('#payment-type-info').show();
                    $('#payment-type-name').html(data);
                } else {
                    $('#payment-type-info').hide();
                }

                if (data == 'MONTHLY') {
                    $('#invoice-date').hide();
                } else {
                    $('#invoice-date').show();
                }
            }
        );
    });
</script>