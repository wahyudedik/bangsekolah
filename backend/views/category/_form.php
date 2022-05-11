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
			    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
			    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'jpg/png'],
                    'pluginOptions' => [
                        'showPreview' => false,
                    ],
                ])->label('Image (700x400)') ?>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="card">
			<header class="card-header">
				<h2>Content</h2>
			</header>
			<div class="card-body card-content">
	    		<?= $form->field($model, 'content')->textarea(['id' => 'content', 'rows' => 25])->label(false) ?>
			</div>
		</div>
		<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> Save' : '<i class="fas fa-save"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	</div>
</div>

<script type="text/javascript">
	/**
	 * TinyMce plugin
	 * Usage and documentation:
	 * 
	 * https://www.tiny.cloud/docs/demo/full-featured/
	 * https://www.tiny.cloud/docs/advanced/php-upload-handler
	 * https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_base_path
	 * https://www.tiny.cloud/docs/configure/url-handling/#domainabsoluteurls 
	 */
    tinymce.init({
        selector:'#content',
        paste_as_text: true,
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table paste imagetools wordcount"
		],
		toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link media image | fullscreen  preview",
		images_upload_url: '<?= Url::to(['tinymce/image-upload-url', 'directory' => 'category']) ?>',
		images_upload_base_path: '<?= Yii::getAlias('@fronturl/uploads/category/') ?>',
		relative_urls : false,
		remove_script_host : false,
		document_base_url : '<?= Yii::getAlias('@fronturl') ?>',
    });
</script>

<?php ActiveForm::end(); ?>