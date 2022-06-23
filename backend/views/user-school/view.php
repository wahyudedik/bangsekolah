<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UserSchool */

$this->title = $model->user->name;
$this->params['breadcrumbs'][] = ['label' => 'Daftar User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['user-school/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-lock"></i> Change Password', ['change-password', 'id' => $model->user_id], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Apakah Anda yakin hapus data ini?");',
                    ]) ?>
                </div>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table table-striped table-bordered detail-view'
                    ],
                    'attributes' => [
                    // 'id',
                    ['attribute' => 'user.name', 'label' => 'Nama User'],
                    ['attribute' => 'user.username', 'label' => 'Username'],
                    ['attribute' => 'user.email', 'label' => 'Email'],
                    ['attribute' => 'user.role.name', 'label' => 'Role'],
                    'created_at',
                    'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
