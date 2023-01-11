<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Setting;

$baseUrl = Yii::$app->request->baseUrl;
$settingPerusahaan = Setting::find()->where(['key' => 'nama-perusahaan'])->one();
$perusahaan = 'BANKSEKOLAH';
if ($settingPerusahaan) $perusahaan = $settingPerusahaan->nilai;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <?= Html::csrfMetaTags() ?>

    <title>BANKSEKOLAH</title>

    <!-- Bootstrap core CSS -->
    <?= $this->render('@app/views/layouts/css') ?>
</head>

<body class="login-page">
    <div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <!-- <a href="#" class="h1">BANKSEKOLAH</a> -->
            <img src="<?= $baseUrl ?>/images/banksekolah-background.jpg" class="img img-responsive">
        </div>
        <div class="card-body">
            <h5 class="login-box-msg" style="padding-bottom: 0px;">BANKSEKOLAH</h5>
            <p class="login-box-msg">Sign in to start your session</p>
            <?php
            foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
            }
            ?>
            
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-signin'], 
                'fieldConfig' => [
                'options' => [
                    'tag' => false,
                ],
            ],]); ?>
                <div class="input-group mb-3">
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username'])->label(false) ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>
                    <div class="input-group-append">
                        <div class="input-group-text" id="toggle-password">
                            <span id="password-button" class="fas fa-eye-slash"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <!-- <div class="icheck-primary">
                            <input type="checkbox" id="remember" />
                            <label for="remember">
                                Remember Me
                            </label>
                        </div> -->
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

</body>
</html>

<style type="text/css">
    #btn-login {
        height: 35px;
        font-size: 1.1em;
    }
    .help-block {
        /*display: none;*/
        position: absolute;
        top: 40px;
        background: #dc3545;
        border-radius: 5px;
        padding: 3px;
        color: white;
        font-size: 11px;
    }
    .help-block:empty {
        display: none;
    }
</style>

<script src="<?= $baseUrl ?>/theme/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $("#toggle-password").on('click', function(event) {
        event.preventDefault();
        if($('#loginform-password').attr("type") == "text"){
            $('#loginform-password').attr('type', 'password');
            $('#password-button').addClass( "fa-eye-slash" );
            $('#password-button').removeClass( "fa-eye" );
        }else if($('#loginform-password').attr("type") == "password"){
            $('#loginform-password').attr('type', 'text');
            $('#password-button').removeClass( "fa-eye-slash" );
            $('#password-button').addClass( "fa-eye" );
        }
    });
</script>