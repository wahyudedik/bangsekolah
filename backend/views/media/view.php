<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2 class="pull-left"><?= Html::encode($this->title) ?></h2>
                <div class="filter-block pull-right">
                    <?= Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash fa-lg"></i> Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </header>
            <div class="card-body">
                <?= $model->imageLarge ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'slug:ntext',
                        'title',
                        'url:ntext',
                        // 'position',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>