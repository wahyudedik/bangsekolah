<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\StockOpnameDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-opname-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'stock_opname_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'stock_product')->textInput() ?>

    <?= $form->field($model, 'stock_opname')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'checked_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
