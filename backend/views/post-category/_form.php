<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\PostCategory;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">

			    <?= $form->field($model, 'parent_id')->dropDownList(
			    	ArrayHelper::map(PostCategory::find()->where(['parent_id' => 0])->asArray()->all(), 'id', 'name'),
			    	['prompt' => '-- Choose parent (optional) --']
			    ) ?>

			    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

				<div class="form-group">
			        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			    </div>
			</div>
		</div>
	</div>	
</div>
<?php ActiveForm::end(); ?>