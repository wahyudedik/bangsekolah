<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Subscribe Form';
?>

<div class="container">
    <div class="spacer-50"></div>
    <div class="row">
        <div class="col-md-9">
            <h1 style="margin-top: 5px; overflow-wrap: break-word;">Subscribe Form</h1>
            <h3 style="margin-top: 0px;">Subscribe to the latest headlines in society, stories, success, and more!</h3>
            <p>Enter the email address where you’d like to receive these newsletters.</p>
            <div class="spacer-30"></div>
            <div class="row">
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Enter your email'])->label(false) ?>
                    <?= Html::submitButton('Join the club', ['class' => 'btn btn-danger']) ?>
                    <?php ActiveForm::end(); ?>
                    <br>
                    <p style="color: grey; font-size: 12px; line-height: 15px;">
                        By subscribing, you agree to our privacy policy.<br>
                        This site is protected by reCAPTCHA, and the Google Privacy Policy and Terms of Service apply.
                    </p>
                </div>
            </div>
            <div class="spacer-50"></div>
        </div>
    </div>
</div>