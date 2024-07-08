<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $school common\models\School */
/* @var $admin common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Register Sekolah';
$today = new \DateTime();
$expiredDate = $today->modify('+1 month')->format('Y-m-d');
$baseUrl = Yii::$app->request->baseUrl;

?>

<main id="main">
    <section class="d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="card mt-4">
                        <div class="card-body">
                            <?php $form = ActiveForm::begin(['id' => 'login-form',]); ?>
                            <h3>Data Sekolah</h3>
                            <?= $form->field($school, 'name')->textInput() ?>
                            <?= $form->field($school, 'number')
                                ->textInput([
                                    'placeholder' => 'Hanya boleh terdiri dari huruf kapital, tanpa spasi dan simbol.'
                                ]) ?>
                            <?= $form->field($school, 'address')->textArea() ?>
                            <?= $form->field($school, 'city')->textInput() ?>
                            <?= $form->field($school, 'phone')->textInput() ?>
                            <?= $form->field($school, 'expired_date')
                                ->hiddenInput(['value' => $expiredDate])
                                ->label(false); ?>

                            <h3>Data User</h3>
                            <?= $form->field($admin, 'name')->textInput() ?>
                            <?= $form->field($admin, 'email')
                                ->textInput([
                                    'placeholder' => 'Format email harus valid.'
                                ]) ?>
                            <?= $form->field($admin, 'username')
                                ->textInput([
                                    'placeholder' => 'Hanya boleh terdiri dari huruf kecil, tanpa spasi.'
                                ]) ?>
                            <?= $form->field($admin, 'password')->passwordInput() ?>
                            <?= $form->field($admin, 'confirm_password')->passwordInput(['label' => 'Ulangi Password']) ?>

                            <div class="input-group">
                                <?= $form->field($school, 'captcha')->widget(Captcha::className(), [
                                    'attribute' => ['autocomplete' => 'off', 'autocorrect' => 'off'],
                                    'options' => [
                                        'placeholder' => 'Masukkan kode captcha',
                                        'class' => 'form-control'
                                    ],
                                ])->label(false) ?>
                            </div>

                            <div class="form-group">
                                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success mt-3']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="text/javascript">
    $('#refresh-captcha').click(function() {
        event.preventDefault();
        $('#user-captcha-image').trigger('click');
    });

    $('#loginform-captcha').attr('autocomplete', 'off');
    $('#loginform-captcha').attr('autocorrect', 'off');
</script>