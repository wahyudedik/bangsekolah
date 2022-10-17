<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Ubah Tagihan: ' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-header">
                <h5>Ubah Nominal</h5>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'amount')->textInput(['value' => $model->amount, 'type' => 'number', 'min' => $model->paid_amount]) ?>
                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['id' => 'btn-submit', 'class' => 'btn btn-success', 'onclick' => 'return confirm("Yakin data di atas sudah benar?");']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
