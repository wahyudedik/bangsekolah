<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Setting Nota';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header">
        <h5>Pengaturan Nota</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'nota_format')->dropDownList([
                    'payment-receipt-format-1' => 'Nota Format 1',
                    'payment-receipt-format-2' => 'Nota Format 2',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>