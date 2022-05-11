<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2 class="pull-left">Detail <?= Html::encode($this->title) ?></h2>
                <div class="filter-block pull-right">
                    <?= Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php
                    if (count($model->majorCategories) == 0 ) {
                        echo Html::a('<i class="fas fa-trash fa-lg"></i> Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                    ?>
                </div>
            </header>
            <div class="card-body">
                <?= $model->imageLarge ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'slug',
                        'name',
                        'description:ntext',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                Content
            </header>
            <div class="card-body">
                <?= $model->content ?>
            </div>
        </div>
    </div>
</div>