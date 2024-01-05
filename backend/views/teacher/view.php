<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Guru', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$user = Yii::$app->user->identity;
?>
<div class="row">

    <div class="col-lg-6 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['teacher/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?php
                    // if ($user->role_id == 2) {
                    // echo Html::a('<i class="nav-icon fas fa-sign-in-alt"></i> Pindah Kelas', ['change-class', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    // }                     
                    ?>
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Hapus / Mutasi', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Apakah Anda yakin hapus / mutasi guru ini?");',
                    ]) ?>
                </div>
            </header>

            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //'id',
                        //'school_id',
                        'nik',
                        'name',
                        'class',
                        'gender',
                        'year',
                        'finger_code',
                        //'status',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>