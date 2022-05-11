<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h2 class="pull-left">Detail <?= Html::encode($this->title) ?></h2>
                <div class="filter-block pull-right">
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
                        'description:ntext',
                        'controller',
                        ['attribute' => 'iconRaw', 'format' => 'raw'],
                        [
                            'attribute' => 'actionRaw',
                            'format' => 'raw',
                            'contentOptions' => ['style' => ['max-width' => '100%']]
                        ],
                        ['attribute' => 'visibilityLabel', 'format' => 'raw'],
                        ['attribute' => 'parent.name', 'label' => 'Parent'],
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>  
</div>