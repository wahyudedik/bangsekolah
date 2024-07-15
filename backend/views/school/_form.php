<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model common\models\School */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'number')->textInput() ?>
                <?= $form->field($model, 'address')->textArea() ?>
                <?= $form->field($model, 'city')->textInput() ?>
                <?= $form->field($model, 'phone')->textInput() ?>
                <?= $form->field($model, 'expired_date')->textInput() ?>
                <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                    'attribute' => ['autocomplete' => 'off', 'autocorrect' => 'off'],
                    'options' => [
                        'placeholder' => 'Masukkan kode captcha',
                        'class' => 'form-control'
                    ],
                ])->label(false) ?>

                <div class="form-group">
                    <?php
                    if ($model->isNewRecord) {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['school/index'], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['school/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>