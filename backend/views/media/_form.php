<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<header class="card-header">
				<h2><?= Html::encode($this->title) ?></h2>
			</header>
			<div class="card-body">
			    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
			    <?php //= $form->field($model, 'url')->textarea(['rows' => 3]) ?>
			    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'jpg/png'],
                    'pluginOptions' => [
                        'showPreview' => false,
                    ],
                ])->label('Image (1920x500)') ?>
			    <?= $model->image ? $model->imageLarge : '' ?>
				<div class="form-group">
			        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			    </div>
			</div>
		</div>
	</div>	
</div>

<?php ActiveForm::end(); ?>