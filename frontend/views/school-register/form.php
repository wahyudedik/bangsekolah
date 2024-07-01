<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\validators\RegularExpressionValidator;
use yii\validators\EmailValidator;

/* @var $this yii\web\View */
/* @var $school common\models\School */
/* @var $admin common\models\User */
/* @var $form yii\widgets\ActiveForm */

$today = new \DateTime();
$expiredDate = $today->modify('+1 month')->format('Y-m-d');
$baseUrl = Yii::$app->request->baseUrl;

?>
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="#" class="logo d-flex align-items-center">
            <img src="<?= $baseUrl ?>/img/logo.png" alt="" />
            <span>BANK SEKOLAH</span>
        </a>
    </div>
</header>
<!-- End Header -->
<section id="hero" class="hero d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(); ?>
                        <h3>Data Sekolah</h3>
                        <?= $form->field($school, 'name')->textInput() ?>
                        <?= $form->field($school, 'number')
                            ->textInput()
                            ->hint('Hanya boleh terdiri dari huruf kapital, tanpa spasi dan simbol.') ?>
                        <?= $form->field($school, 'address')->textArea() ?>
                        <?= $form->field($school, 'city')->textInput() ?>
                        <?= $form->field($school, 'phone')->textInput() ?>
                        <?= $form->field($school, 'expired_date')
                            ->hiddenInput(['value' => $expiredDate])
                            ->label(false); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Data User</h3>
                        <?= $form->field($admin, 'name')->textInput() ?>
                        <?= $form->field($admin, 'email')
                            ->textInput()
                            ->hint('Format email harus valid.') ?>
                        <?= $form->field($admin, 'username')
                            ->textInput()
                            ->hint('Hanya boleh terdiri dari huruf kecil, tanpa spasi.') ?>
                        <?= $form->field($admin, 'password')->passwordInput() ?>
                        <?= $form->field($admin, 'confirm_password')->passwordInput(['label' => 'Ulangi Password']) ?>
 
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