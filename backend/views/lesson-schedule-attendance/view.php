<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Schedule Attendances', 'url' => ['index']];
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
            'lesson_schedule_id',
            'student_id',
            'status',
            'datetime',
            'note:ntext',
            'created_at',
            'updated_at',
                        ],
                    ]) ?>
            </div>
        </div>
    </div>
</div>