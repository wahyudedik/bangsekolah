<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Outlet */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'status')->dropDownList([
                    'ACTIVE' => 'ACTIVE',
                    'NONACTIVE' => 'NONACTIVE',
                ]) ?>
                <div class="form-group">
                    <?php
                    if ($model->isNewRecord) {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['outlet/index'], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['outlet/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
