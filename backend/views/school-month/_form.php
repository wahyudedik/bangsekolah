<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'month')->textInput(['maxlength' => true]) ?>

                <div class="callout callout-info">
                    <h5>Urutan Bulan</h5>
                    <p>Merupakan urutan bulan dalam tahun dengan format MM.<br>Misal Juni (06), Juli (07), Agustus (08) dan sebagainya</p>
                </div>

                <div class="form-group">
                    <?php
                    if ($model->isNewRecord) {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['school-month/index'], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['school-month/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>                    
                    <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
