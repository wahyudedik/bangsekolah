<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Recipe */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'product_id')->dropDownList(\common\models\Product::getList())->label('Product Output') ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Recipe Name') ?>
                <?= $form->field($model, 'expected_quantity')->textInput(['maxlength' => true])->label('Expected Quantity') ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                <div class="form-group">
                    <?php
                    if ($model->isNewRecord) {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['recipe/index'], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['recipe/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
