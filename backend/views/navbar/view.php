<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Navbar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <div class="card-tools float-right">
                    <?php
                    if (User::can('menu', 'update'))
                        echo Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    ?>
                    <?php
                    if (User::can('menu', 'delete'))
                        Html::a('<i class="fas fa-trash fa-lg"></i> Delete', ['delete', 'id' => $model->id], [
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
                    'template' => function($attribute, $index, $widget){
                        if($attribute['value'])
                            return "<tr><th>{$attribute['label']}</th><td>{$attribute['value']}</td></tr>";
                    },
                    'attributes' => [
                        'name',
                        'url',
                        ['attribute' => 'parent.name', 'label' => 'Parent'],
                        'position',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>  
</div>