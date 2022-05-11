<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<header class="card-header">
				<h2><?= Html::encode($this->title) ?></h2>
			</header>
			<div class="card-body">
			    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>
			    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
			    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>
				<div class="form-group">
			        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			    </div>
			</div>
		</div>
	</div>	
</div>
<?php ActiveForm::end(); ?>