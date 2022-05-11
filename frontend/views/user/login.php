<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Lang;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Login';
?>

<div class="content-wrapper">
	<section class="text-center" style="margin-top: 50px;">
		<div class="container">
			<div class="page-title__holder">
				<h1 class="page-title__title">Login</h1>
				<p class="page-title__subtitle">Login untuk masuk ke menu member PT. PUS</p>
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
                                        <label for="name">Email</label>
                                        <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Password</label>
                                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label(false) ?>
                                    </div>
                                    <div class="form-group captcha">
                                        <div class="g-recaptcha" data-sitekey="6Lcm6MIZAAAAAAoOod0SEPUeynMfF9L9dashFW0G"></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <input type="submit" class="btn btn--lg btn--alt-color btn--wide btn--button" value="Login" />
                        <?php ActiveForm::end(); ?>
                        <br>
                        Not a member? <?= Html::a('Register here', ['user/register']) ?>
			        </div>
				</div>
			</div>
        </div>
    </section>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>