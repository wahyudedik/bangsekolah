<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-6">
                
                <?= $form->field($model, 'product_category_id')->dropDownList(\common\models\ProductCategory::getList()) ?>
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'unit')->dropDownList([
                    'Pcs' => 'Pcs',
                    'Pack' => 'Pack',
                    'Ball' => 'Ball',
                    'Lt' => 'Lt',
                    'Kg' => 'Kg',
                    'Gr' => 'Gr',
                    'Mg' => 'Mg',
                ]) ?>
                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'price_sale')->textInput(['type' => 'number']) ?>
                <div class="form-group">
                    <?php
                    if ($model->isNewRecord) {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['product/index'], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['product/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
