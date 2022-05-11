<?php
use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
?>
<div class="table-responsive" id="grid-banner">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-hover'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'imageSmall', 'format' => 'raw'],
            'titleSort',
            // 'url',
            'created_at',
            // 'position',
            ['attribute' => 'linkPosition', 'label' => 'Position', 'format' => 'raw'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{view}{update}{delete}', 
                'contentOptions' => ['style' => 'width:150px;'],
                'buttons' => [
                    'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                    'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                    'delete' => function ($url, $model) { return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']).' '; },
                ]
            ],
        ],
    ]); ?>
</div>