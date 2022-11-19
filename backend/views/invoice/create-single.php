<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\PaymentType;

$paymentTypes = PaymentType::find()->all();

$this->title = 'Buat Tagihan';
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student->name, 'url' => ['invoices', 'student' => $model->student_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-create">

    <div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Nama Pembayaran</h5>
                        <p><?= $model->student->name ?></p> 
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
                                <?php
                                foreach ($paymentTypes as $type) {
                                    echo '<li>'.$type->label.': '.$type->description.'</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div id="invoice-date">
                            <?= $form->field($model, 'date')->textInput(['type' => 'date', 'value' => date('Y-m-d')])->label('Tanggal Tagihan') ?>
                            <?= $form->field($model, 'due_date')->textInput(['type' => 'date', 'value' => date('Y-m-t')])->label('Tanggal Jatuh Tempo') ?>
                        </div>
                        <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'value' => '0'])->label('Nominal Tagihan') ?>
                        <div class="form-group">
                            <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoices', 'student' => $model->student_id], ['class' => 'btn btn-warning']) ?>
                            <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['id' => 'btn-submit', 'class' => 'btn btn-success', 'onclick' => 'return confirm("Yakin data di atas sudah benar?");']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

</div>
