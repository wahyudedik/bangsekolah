<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="row" id="user-profile">
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="card">
            <header class="card-header">
                <h2><?= $model->school->name ?></h2>
            </header>
            <div class="card-body">
                <?php
                if ($model->school->image == null) {
                    $src = 'default-user.png';
                } else {
                    $src = $model->school->image;
                }
                ?>
                <img src="<?= $baseUrl . '/uploads/school/' . $src ?>" alt="" class="profile-img img-responsive center-block">
                <div class="profile-since">
                    <br>Registered at: <?= date('M', strtotime($model->created_at)) . ' ' . date('Y', strtotime($model->created_at)) ?>
                </div>
                <br>
                <div class="profile-message-btn center-block text-center">
                    <?= Html::a('<i class="fas fa-lock"></i> Change Password', ['profile/change-password'], ['class' => 'btn btn-primary']) ?>
                    <br><br>
                    <?= Html::a('<i class="fas fa-pen"></i> Edit Profile', ['profile/update', 'id_user' => $model->user_id], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

    </div>
    
    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <h2 class="filter-block float-left">Profile</h2>
            </header>
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    ['attribute' => 'user.name', 'format' => 'raw', 'label' => 'Nama'],
                    ['attribute' => 'user.username', 'format' => 'raw', 'label' => 'Username'],
                    ['attribute' => 'user.email', 'format' => 'raw', 'label' => 'Email'],
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>
</div>
