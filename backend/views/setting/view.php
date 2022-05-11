<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Setting', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2 class="pull-left">Detail <?= Html::encode($this->title) ?></h2>
                <div class="filter-block pull-right">
                    <?= Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php /*= Html::a('<i class="fas fa-trash fa-lg"></i> Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ])*/ ?>
                </div>
            </header>
            
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'key',
                    'name',
                    'description:ntext',
                    'value:ntext',
                    // 'status',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>