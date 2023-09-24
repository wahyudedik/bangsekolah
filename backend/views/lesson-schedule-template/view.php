<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Pelajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card">
    <header class="card-header">
        <div class="filter-block pull-right">
            <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
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
                            'id',
            'school_id',
            'school_class_id',
            'day_id',
            'hour',
            'teacher_id',
            'lesson_id',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
                        ],
                    ]) ?>
            </div>
        </div>
    </div>
</div>