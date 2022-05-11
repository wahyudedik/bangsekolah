<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\Role;

$baseUrl = Yii::$app->request->baseUrl;
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

                <?php 
                    if(!$school_id) {
                        echo $form->field($model, 'role_id')->dropDownList(Role::getList());
                    }
                ?>

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
            <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>