<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Ubah Password';
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="row" id="user-profile">
    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <h5><?= $this->title ?></h5>
            </header>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php $form = ActiveForm::begin(); ?>
                            <label>Password Baru</label>
                            <div class="input-group mb-3">
                                <?= $form->field($model, 'password')->passwordInput()->label(false) ?>
                                <div class="input-group-append">
                                    <div class="input-group-text" id="toggle-password-1">
                                        <span id="password-button-1" class="fas fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <label>Ulangi Password</label>
                            <div class="input-group mb-3">
                                <?= $form->field($model, 'confirm_password')->passwordInput()->label(false) ?>
                                <div class="input-group-append">
                                    <div class="input-group-text" id="toggle-password-2">
                                        <span id="password-button-2" class="fas fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="col-md-6">
                        <h5>Note: </h5>
                        <div class="alert alert-info">
                            Use a password with a combination of letters, numbers, and symbols. The stronger the password the more secure your account. Do not share password information with anyone.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .form-group {
        margin-bottom: 0px !important;
    }
</style>

<script type="text/javascript">
    $("#toggle-password-1").on('click', function(event) {
        event.preventDefault();
        if($('#user-password').attr("type") == "text"){
            $('#user-password').attr('type', 'password');
            $('#password-button-1').addClass( "fa-eye-slash" );
            $('#password-button-1').removeClass( "fa-eye" );
        }else if($('#user-password').attr("type") == "password"){
            $('#user-password').attr('type', 'text');
            $('#password-button-1').removeClass( "fa-eye-slash" );
            $('#password-button-1').addClass( "fa-eye" );
        }
    });

    $("#toggle-password-2").on('click', function(event) {
        event.preventDefault();
        if($('#user-confirm_password').attr("type") == "text"){
            $('#user-confirm_password').attr('type', 'password');
            $('#password-button-2').addClass( "fa-eye-slash" );
            $('#password-button-2').removeClass( "fa-eye" );
        }else if($('#user-confirm_password').attr("type") == "password"){
            $('#user-confirm_password').attr('type', 'text');
            $('#password-button-2').removeClass( "fa-eye-slash" );
            $('#password-button-2').addClass( "fa-eye" );
        }
    });
</script>
