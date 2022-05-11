<?php
use yii\helpers\Html;
use common\models\User;

$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="card">
    <header class="card-header">
        <h2><?= $model->name ?></h2>
    </header>
    <div class="card-body">
        <?php
        if ($model->image == null) {
            $src = 'default-user.png';
        } else {
            $src = $model->image;
        }
        ?>
        <img src="<?= $baseUrl . '/uploads/user/' . $src ?>" alt="" class="profile-img img-responsive center-block">
        <div class="profile-since">
            <br>Role: <?= $model->role->name ?>
            <br>Registered at: <?= date('M', strtotime($model->created_at)) . ' ' . date('Y', strtotime($model->created_at)) ?>
            <br>Email: <?= $model->email ?>
        </div>
        <br>
        <?php
        // yang boleh edit profil hanya admin atau logged in user
        // Yii::$app->user->identity->id == $model->id
        
        if (Yii::$app->user->identity->id == $model->id) {
            echo '<div class="profile-message-btn center-block text-center">';
            echo Html::a('<i class="fas fa-lock"></i> Change Password', ['user/change-password'], ['class' => 'btn btn-primary']).'<br><br>';
            echo Html::a('<i class="fas fa-pen"></i> Edit Profile', ['user/update'], ['class' => 'btn btn-primary']);
            echo '</div>';
        } else if (Yii::$app->request->get('id')) {
            echo '<div class="profile-message-btn center-block text-center">';
            if (User::can('user', 'change-password'))
                echo Html::a('<i class="fas fa-lock"></i> Change Password', ['user/change-password', 'id' => $model->id], ['class' => 'btn btn-primary']).'<br><br>';
            if (User::can('user', 'update'))
                echo Html::a('<i class="fas fa-pen"></i> Edit Profile', ['user/update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo '</div>';
        }
        ?>
    </div>
</div>