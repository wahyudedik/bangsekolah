<?php

use yii\helpers\Html;
use common\models\Role;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Update Profile: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

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

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'logo')->fileInput()->label('Logo Sekolah') ?>

                <div class="form-group">
                    <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['profile/index'], ['class' => 'btn btn-warning']); ?>

                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                </div>
			</div>

		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
