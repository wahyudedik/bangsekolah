<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = ['label' => 'Daftar User', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $user_school->id]];

$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="row" id="user-profile">
    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <h2><?= $this->title ?></h2>
            </header>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php $form = ActiveForm::begin(); ?>
                            <?= $form->field($model, 'password')->passwordInput() ?>
                            <?= $form->field($model, 'confirm_password')->passwordInput() ?>
                            <div class="form-group">
                                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="col-md-6">
                        <h2>Note: </h2>
                        <div class="alert alert-info">
                            Use a password with a combination of letters, numbers, and symbols. The stronger the password the more secure your account. Do not share password information with anyone.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>