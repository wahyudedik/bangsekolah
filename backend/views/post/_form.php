<?php

use common\models\PostCategory;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use common\models\User;

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'form-post']]); ?>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body card-content">
                <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'title')->textInput(['placeholder' => 'Enter title here'])->label('<span class="text-red">*</span>Title') ?>
                <?= $form->field($model, 'slug')->textInput(['placeholder' => 'enter-title-here'])->label(false) ?>
                <button id="btn-edit-slug" class="btn-edit-slug" type="button"><i class="fas fa-pen"></i></button>
                <span id="span-post-slug" style="margin-top: -10px; margin-left:  5px; margin-bottom: 10px; color: grey; font-style: italic;">Slug: <?= $model->slug ?></span>
                <br><br>
                <?= $form->field($model, 'content')->textarea(['id' => 'content', 'rows' => 15])->label(false) ?>
                <p>
                    <strong>EDITOR NOTE:</strong> approved image extension: jpg, jpeg, png. max file name: 20 characters
                </p>
                <?= $form->field($model, 'description')->textarea(['rows' => 2])->label('<span class="text-red">*</span>Description / Excerpt') ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                            'options' => ['accept' => 'jpg/jpeg/png'],
                            'pluginOptions' => [
                                'showPreview' => true,
                            ],
                        ])->label('<span class="text-red">*</span>Image (jpg/jpeg/png) - Resolution: 1240x892') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'video')->widget(FileInput::classname(), [
                            'options' => ['accept' => 'mp4'],
                            'pluginOptions' => [
                                'showPreview' => true,
                            ],
                        ])->label('Video (mp4) - Optional, 3 MB max file size') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <?php if (!$model->isNewRecord) : ?>
            <?php if ($model->status == 1) : ?>
                <div class="alert alert-success">
                    <h5 style="margin-bottom: 0px;"><i class="icon fas fa-check"></i> Published</h5>
                </div>
            <?php else : ?>
                <div class="alert alert-warning">
                    <h5 style="margin-bottom: 0px;"><i class="icon fas fa-pencil-ruler"></i> Draft</h5>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Publish</h3>
            </div>
            <div class="card-body">
                <?php
                if ($model->status == 'ACTIVE') {
                    echo $form->field($model, 'published_at')->textInput(['value' => $model->published_at])->label('Published Date');
                } else {
                    echo $form->field($model, 'scheduled_at')->textInput(['placeholder' => 'Fill schedule datetime'])->label('Scheduled Post');
                }
                ?>
                <button id="btn-draft" class="btn btn-lg btn-warning"><i class="fas fa-save"></i> Save Draft</button>
                <?php
                if ($model->status == 1) {
                    echo '<button id="btn-publish" class="btn btn-lg btn-success"><i class="fas fa-save"></i> Update Post</button>';
                } else {
                    echo '<button id="btn-publish" class="btn btn-lg btn-success"><i class="fas fa-upload"></i> Publish</button>';
                }
                ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php
                if ($user->role_id == 1) {
                    echo $form->field($model, 'created_by')->dropDownList(User::getUser())->label('<i class="fas fa-user"></i> Author');
                } else {
                    echo $form->field($model, 'created_by')->hiddenInput(['value' => $user->id])->label(false);
                }
                ?>
                <?= $form->field($model, 'post_category_id')->dropDownList(PostCategory::getListTree())->label('<i class="fas fa-tags"></i> Category') ?>
                <?= $form->field($model, 'tags')->widget(Select2::classname(), [
                    'options' => ['placeholder' => 'Input some tags, separate with comma ...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [','],
                        'maximumInputLength' => 30
                    ],
                ])->label('<i class="fas fa-hashtag"></i> Tags'); ?>
                <div class="form-group">
                    <?= $model->image ? '<label><i class="fas fa-image"></i> Image</label>' . $model->imageLarge : '' ?>
                </div>
                <div class="form-group">
                    <?= $model->video ? '<label><i class="fas fa-video"></i> Video</label>' . $model->videoEmbed : '' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .btn-edit-slug {
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .text-red {
        color: red;
    }
</style>

<script src="https://cdn.tiny.cloud/1/k0wgrg0i4thwyn0ty9le1bg78pe1ms7xp48c2e0s6ysnny39/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<!-- daterangepicker -->
<script src="<?= $baseUrl ?>/theme/plugins/moment/moment.min.js"></script>
<script src="<?= $baseUrl ?>/theme/plugins/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?= $baseUrl ?>/theme/plugins/daterangepicker/daterangepicker.css">

<script type="text/javascript">
    $(document).ready(function() {
        $('.field-post-slug').toggle();
    });

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
        selector: '#content',
        paste_as_text: true,
        // contextmenu_never_use_native: true,
        contextmenu: false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste imagetools wordcount",
        ],
        toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link media image | fullscreen  preview | formatselect",
        images_upload_url: '<?= Url::to(['tinymce/image-upload-url', 'directory' => 'posts']) ?>',
        images_upload_base_path: '<?= Yii::getAlias('@fronturl/uploads/posts/') ?>',
        relative_urls: false,
        remove_script_host: false,
        document_base_url: '<?= Yii::getAlias('@fronturl') ?>',
        content_style: 'body { font-size: 12pt; font-family: Helvetica; }',
        image_dimensions: false,
        image_caption: true,
        image_class_list: [{
            title: 'Responsive',
            value: 'img-responsive'
        }]
    });

    $('#post-title').keyup(function() {
        var slug = $.trim($(this).val());
        slug = slug.toLowerCase();
        slug = slug.replace(/[^a-zA-Z0-9]+/g, '-');
        $('#post-slug').val(slug);
        $('#span-post-slug').html('Slug: ' + slug);
    });

    $('#btn-draft').click(function() {
        $('#post-status').val('NONACTIVE');
        $('#form-post').submit();
    });

    $('#btn-publish').click(function() {
        $('#post-status').val('ACTIVE');
        $('#form-post').submit();
    });

    $('#btn-edit-slug').click(function() {
        $('.field-post-slug').toggle();
    });

    // Scheduled at
    $('#post-scheduled_at').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        autoUpdateInput: false,
        locale: {
            format: 'Y-M-DD H:mm:ss'
        }
    });
    $('#post-scheduled_at').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('Y-M-DD H:mm:ss'));
    });
    $('#post-scheduled_at').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Published at
    $('#post-published_at').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        autoUpdateInput: false,
        locale: {
            format: 'Y-M-DD H:mm:ss'
        }
    });
    $('#post-published_at').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('Y-M-DD H:mm:ss'));
    });
    $('#post-published_at').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>

<?php ActiveForm::end(); ?>
