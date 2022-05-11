<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Lang;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Update Profile';
?>

<div class="content-wrapper">
    <section class="text-center" style="margin-top: 50px;">
        <div class="container">
            <div class="page-title__holder">
                <h1 class="page-title__title">Update Profile</h1>
                <p class="page-title__subtitle">Update your profile and your personal data</p>
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
                                        <label for="name">Full Name</label>
                                        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <?= $form->field($model, 'gender')->dropDownList([
                                            'male' => 'Male',
                                            'female' => 'Female',
                                        ])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="place_of_birth">Place of Birth</label>
                                        <?= $form->field($model, 'place_of_birth')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <?php //= $form->field($model, 'date_of_birth')->textInput(['maxlength' => true])->label(false) ?>
                                        <?= $form->field($model, 'date_of_birth')->widget(DatePicker::classname(), [
                                            'options' => ['placeholder' => 'Enter birth date'],
                                            'pluginOptions' => [
                                                'autoclose'=>true,
                                                'format' => 'yyyy-mm-dd',
                                            ]
                                        ])->label(false) ?>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <?= $form->field($model, 'address')->textArea(['rows' => 3])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_number">ID Number</label>
                                        <?= $form->field($model, 'id_number')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <?= $form->field($model, 'city')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <?= $form->field($model, 'state')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="nationality">Nationality</label>
                                        <?= $form->field($model, 'nationality')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Photo</label>
                                        <?= $form->field($model, 'image')->fileInput()->label('Profile picture')->label(false) ?>
                                    </div>
                                    <?php
                                    if ($model->user->image !== null) {
                                        echo Html::img($baseUrl.'/uploads/user/large-'.$model->user->image, ['width' => '50%']);
                                    } else {
                                        echo Html::img($baseUrl.'/images/default-user.png', ['width' => '50%']);
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="skill">Please describe your skills</label>
                                        <?= $form->field($model, 'skill')->textArea(['rows' => 6])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn--lg btn--alt-color btn--wide btn--button" value="Update Profile" />
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>