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
<?= $form->field($model, 'school_class_id')->textInput() ?>
<?= $form->field($model, 'date')->textInput() ?>
<?= $form->field($model, 'lesson_hour')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'teacher_id')->textInput() ?>
<?= $form->field($model, 'lesson_id')->textInput() ?>
<?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'created_at')->textInput() ?>
<?= $form->field($model, 'updated_at')->textInput() ?>
<?= $form->field($model, 'created_by')->textInput() ?>
<?= $form->field($model, 'updated_by')->textInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
