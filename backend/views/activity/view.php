<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->user->name.' > '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Activitas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card">
    <header class="card-header">
        <div class="filter-block pull-right">
        </div>
    </header>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table table-striped table-hover'
                    ],
                    'attributes' => [
                        ['attribute' => 'school.name', 'label' => 'Sekolah'],
                        ['attribute' => 'user.name', 'label' => 'Nama Pengguna'],
                        ['attribute' => 'user.username', 'label' => 'Username'],
                        'name:ntext',
                        'description:ntext',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>