<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use common\models\Menu;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<!-- <header class="card-header">
				<h2><?= Html::encode($this->title) ?></h2>
			</header> -->
			<div class="card-body">
				
			    <?= $form->field($model, 'parent_id')->dropDownList(Menu::getListTree(), ['prompt' => '(No parent)']) ?>

				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

			    <?= $form->field($model, 'controller')->textInput(['maxlength' => true]) ?>

				<?= $form->field($model, 'json_action')->widget(Select2::classname(), [
				    'options' => ['placeholder' => 'Daftarkan action', 'multiple' => true],
				    'pluginOptions' => [
				        'tags' => true,
				        'tokenSeparators' => [',', ' '],
				        'maximumInputLength' => 30
				    ],
				])->label('Action'); ?>

				<?= $form->field($model, 'default_action')->textInput(['maxlength' => true]) ?>

			    <?= $form->field($model, 'icon')->textInput()->label('Icon (Font Awesome icon, for example: fas fa-home)') ?>
			    
			    <?= $form->field($model, 'visibility')->dropDownList([
			    	1 => 'Show',
			    	0 => 'Hidden',
			    ])->label('Visibility in sidebar') ?>

				<div class="form-group">
			        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			    </div>
			</div>
		</div>
	</div>	
</div>
<?php ActiveForm::end(); ?>