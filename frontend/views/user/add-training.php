<?php
use yii\helpers\Url;
use common\helpers\Lang;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Add Training';
?>

<div class="content-wrapper">
    <section class="text-center" style="margin-top: 50px;">
        <div class="container">
            <div class="page-title__holder">
                <h1 class="page-title__title">Add Training</h1>
                <p class="page-title__subtitle">Add your training detail here</p>
            </div>
        </div>
    </section>
    <section class="section-wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <?php
                    foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
                        echo '<div class="alert alert-' . $key . '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . "</div>\n";
                    }
                    ?>
                    <div class="contact box-shadow-large">
                        <?php $form = ActiveForm::begin(); ?>
                            <div class="row row-30">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">Training Title</label>
                                        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Training Date</label>
                                        <?php //= $form->field($model, 'date')->textInput(['maxlength' => true])->label(false) ?>
                                        <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                                            'options' => ['placeholder' => 'Enter birth date'],
                                            'pluginOptions' => [
                                                'autoclose'=>true,
                                                'format' => 'yyyy-mm-dd',
                                            ]
                                        ])->label(false) ?>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="organizer">Training Organizer</label>
                                        <?= $form->field($model, 'organizer')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn--lg btn--alt-color btn--wide btn--button" value="Save Training" />
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>