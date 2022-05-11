<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Menu;

if($model->isNewRecord) {
	$fullUrl = Url::to([Yii::$app->controller->id.'/'.Yii::$app->controller->action->id, 'role' => $role->id]);
} else {
	$fullUrl = Url::to([Yii::$app->controller->id.'/'.Yii::$app->controller->action->id, 'id' => $model->id]);
}
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<header class="card-header">
				<h2><?= Html::encode($this->title) ?></h2>
			</header>
			<div class="card-body">
				
			    <?= $form->field($model, 'menu_id')->dropDownList(Menu::getListTree(), ['prompt' => 'Pilih menu'])->label('Menu') ?>

				<?= $form->field($model, 'json_privileges')->widget(Select2::classname(), [
					'data' => $arrData,
				    'options' => ['placeholder' => 'Pilih satu atau beberapa ...', 'multiple' => true],
				    'pluginOptions' => [
				        'tags' => true,
				        'tokenSeparators' => [',', ' '],
				        'maximumInputLength' => 30
				    ],
				])->label('Privileges'); ?>

			    <?= $form->field($model, 'parameter')->textInput() ?>

			    <?= $form->field($model, 'label')->textInput(['placeholder' => 'Opsional']) ?>
			    
				<div class="form-group">
			        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			    </div>
			</div>
		</div>
	</div>	
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
	$('#rolemenu-menu_id').change(function(){
		window.location.href = "<?= $fullUrl ?>&menu="+$('#rolemenu-menu_id :selected').val();
	});
</script>