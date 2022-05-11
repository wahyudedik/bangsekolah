<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Outlet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Outlets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="row" id="user-profile">
    <div class="col-lg-3 col-md-4 col-sm-4">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['outlet/index'], ['class' => 'btn btn-warning']) ?>
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
                if (!$user_outlet) {
                    $src = 'default-user.png';
                } else {
                    $user_outlet->user->image == null ? $src = 'default-user.png' : $src = $user_outlet->user->image;
                }
                ?>
                <img src="<?= $baseUrl . '/uploads/user/' . $src ?>" alt="" class="profile-img img-responsive center-block">
                <div class="profile-body" style="text-align: center; padding-top: 10px;">
                    <?php if(!$user_outlet) {
                        echo '<div class="profile-message-btn center-block text-center" style="padding-top: 5px">';
                        echo Html::a('Create User', ['user/create', 'outlet_id' => $model->id], ['class' => 'btn btn-success']);
                        echo "</div>";
                    } else { ?>
                        <div class="profile-since">
                            <br>User admin: <?= $user_outlet->user->name ?>
                            <br>Username: <?= $user_outlet->user->username ?>
                            <br>Registered at: <?= date('M', strtotime($user_outlet->created_at)) . ' ' . date('Y', strtotime($user_outlet->created_at)) ?>
                            <br>Email: <?= $user_outlet->user->email ?>
                        </div>
                        <br>
                        <?= '<div class="profile-message-btn center-block text-center">'; ?>
                        <?= Html::a('<i class="fas fa-lock"></i> Change Password', ['user/change-password', 'id' => $user_outlet->user_id], ['class' => 'btn btn-primary']).'<br><br>'; ?>
                        <?= Html::a('<i class="fas fa-pen"></i> Edit Profile', ['user/update', 'id' => $user_outlet->user_id, 'outlet_id' => $model->id], ['class' => 'btn btn-primary']); ?>
                        <?= '</div>';?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php
                    ?>
                </div>
            </header>
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'email',
                    'address:ntext',
                    'city',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>
</div>