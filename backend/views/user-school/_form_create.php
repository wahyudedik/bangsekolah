<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\Role;

/* @var $this yii\web\View */
/* @var $model common\models\UserSchool */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2>User Detail</h2>
            </header>
            <div class="card-body">

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'role_id')->dropDownList(Role::getRoleSchool()); ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2>Credential</h2>
            </header>
            <div class="card-body">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="form-group">
            <?php
            if ($model->isNewRecord) {
                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['user-school/index'], ['class' => 'btn btn-warning']);
            } else {
                echo Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['user-school/view', 'id' => $model->id], ['class' => 'btn btn-warning']);
            }
            ?>                    
            <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>