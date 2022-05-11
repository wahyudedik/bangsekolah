<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Role', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <div class="filter-block pull-right">
                    <?php
                    if (User::can('role', 'update'))
                        echo Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    ?>
                    <?php
                    if (User::can('role', 'create'))
                        echo Html::a('<i class="fas fa-trash fa-lg"></i> Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    ?>
                </div>
            </header>
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    ['attribute' => 'linkMenu', 'format' => 'raw', 'label' => 'Menu Count'],
                    'description:ntext',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>