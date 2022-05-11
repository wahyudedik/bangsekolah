<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;


?>

<div class="card">
    <div class="card-header">
        <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['recipe/view', 'id' => $recipe->id], ['class' => 'btn btn-warning']) ?>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'product_id')->widget(Select2::classname(), [
                        'data' => \common\models\Product::getList(),
                        'options' => ['placeholder' => 'Find product', 'multiple' => false],
                    ])->label('Product'); ?>
                    <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'min' => 0, 'required']) ?>
                    <?= $form->field($model, 'unit')->dropDownList([
                        'Gr' => 'Gr',
                        'Mg' => 'Mg',
                        'Ml' => 'Ml',
                        'Pcs' => 'Pcs',
                        'Butir' => 'Butir',
                        'Lt' => 'Lt',
                        'Kg' => 'Kg',
                    ]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'note')->textarea(['rows' => 7]) ?>
                </div>
            </div>
            <?= $form->field($model, 'recipe_id')->hiddenInput(['value' => $recipe->id])->label(false) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

