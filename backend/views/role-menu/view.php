<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

$this->title = $model->menu->name;
$this->params['breadcrumbs'][] = ['label' => 'Menu '.$model->role->name, 'url' => ['role-menu/index', 'role' => $model->role->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2 class="pull-left">Detail <?= Html::encode($this->title) ?></h2>
                <div class="filter-block pull-right">
                    <?php
                    if (User::can('role-menu', 'update'))
                        echo Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    if (User::can('role-menu', 'delete'))
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
                    ['attribute' => 'role.name', 'label' => 'Role'],
                    ['attribute' => 'menu.name', 'label' => 'Menu'],
                    'position',
                    ['attribute' => 'privilegeRaw', 'label' => 'Privilege', 'format' => 'raw'],
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>