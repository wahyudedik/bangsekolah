<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'school_id')->textInput() ?>
<?= $form->field($model, 'student_id')->textInput() ?>
<?= $form->field($model, 'date')->textInput() ?>
<?= $form->field($model, 'check_in')->textInput() ?>
<?= $form->field($model, 'check_out')->textInput() ?>
<?= $form->field($model, 'status_check_in')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status_check_out')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'created_at')->textInput() ?>
<?= $form->field($model, 'updated_at')->textInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
