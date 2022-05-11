<?php

use yii\helpers\Html;
use common\models\Role;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<header class="card-header">
				<h2>Identitas User</h2>
			</header>
			<div class="card-body">

                <?= $form->field($model, 'status')->hiddenInput(['value' => 'ACTIVE'])->label(false) ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                <?php 
                    if(!$outlet_id) {
                        echo $form->field($model, 'role_id')->dropDownList(Role::getList());
                    }
                ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <div class="form-group">
                    <?php
                    if ($outlet_id) {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['outlet/view', 'id' => $outlet_id], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a('<i class="fas fa-chevron-left"></i> Back', ['user/index'], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>
			</div>

		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
