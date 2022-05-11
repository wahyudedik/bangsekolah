<?php
use yii\helpers\Url;
use common\helpers\Lang;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Add Experience';
?>

<div class="content-wrapper">
    <section class="text-center" style="margin-top: 50px;">
        <div class="container">
            <div class="page-title__holder">
                <h1 class="page-title__title">Add Experience</h1>
                <p class="page-title__subtitle">Add your working experience here</p>
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
                                        <label for="company_name">Company Name</label>
                                        <?= $form->field($model, 'company_name')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="job_title">Job Title</label>
                                        <?= $form->field($model, 'job_title')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="job_description">Job Description</label>
                                        <?= $form->field($model, 'job_description')->textArea(['rows' => 3])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_salary">Last Salary</label>
                                        <?= $form->field($model, 'last_salary')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn--lg btn--alt-color btn--wide btn--button" value="Save Experience" />
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>