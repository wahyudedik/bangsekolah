<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\School */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sekolah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['school/index'], ['class' => 'btn btn-warning']) ?>
            </header>
            <div class="card-body">
                <div class="profile-status text-center" style="padding-bottom: 10px;">
                    <div class="profile-label badge bg-info">
                        <span class="label label-info">
                            <?= $model->name ?>
                        </span>
                    </div>
                </div>
                <?php
                if (!$user_school) {
                    $src = 'default-user.png';
                } else {
                    $user_school->user->image == null ? $src = 'default-user.png' : $src = $user_school->user->image;
                }
                ?>
                <img src="<?= $baseUrl . '/uploads/user/' . $src ?>" alt="" class="profile-img img-responsive center-block">
                <div class="profile-body" style="text-align: center; padding-top: 10px;">
                    <?php if(!$user_school) {
                        echo '<div class="profile-message-btn center-block text-center" style="padding-top: 5px">';
                        echo Html::a('Buat Admin', ['user/create', 'school_id' => $model->id], ['class' => 'btn btn-success']);
                        echo "</div>";
                    } else { ?>
                        <div class="profile-since">
                            <br>User admin: <?= $user_school->user->name ?>
                            <br>Username: <?= $user_school->user->username ?>
                            <br>Terdaftar pada: <?= date('M', strtotime($user_school->created_at)) . ' ' . date('Y', strtotime($user_school->created_at)) ?>
                            <br>Email: <?= $user_school->user->email ?>
                        </div>
                        <br>
                        <?= '<div class="profile-message-btn center-block text-center">'; ?>
                        <?= Html::a('<i class="fas fa-lock"></i> Ubah Password', ['user/change-password', 'id' => $user_school->user_id], ['class' => 'btn btn-primary']).'<br><br>'; ?>
                        <?= Html::a('<i class="fas fa-pen"></i> Edit Profil', ['user/update', 'id' => $user_school->user_id, 'school_id' => $model->id], ['class' => 'btn btn-primary']); ?>
                        <?= '</div>';?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <div class="filter-block pull-right">
                    <?= Html::a('<i class="fas fa-pen fa-lg"></i> Edit Informasi Sekolah', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                </div>
            </header>
            
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'id',
                    'name',
                    'number',
                    'address',
                    'city',
                    'phone',
                    'status',
                    'expired_date',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>
