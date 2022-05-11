<?php
use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
?>
<div class="table-responsive" id="grid-role-menu">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'tableOptions' => [
            'class' => 'table table-hover'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'parentChildName', 'label' => 'Menu', 'format' => 'raw'],
            [
                'attribute' => 'privilegeRaw',
                'label' => 'Privilege action menu',
                'format' => 'raw',
            ],
            'parameter',
            ['attribute' => 'linkPosition', 'label' => 'Position', 'format' => 'raw'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{view}{update}{delete}', 
                'contentOptions' => ['style' => 'width:150px;'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (User::can('role-menu', 'view'))
                            return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' ';
                    },
                    'update' => function ($url, $model) {
                        if (User::can('role-menu', 'update'))
                            return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' ';
                    },
                    'delete' => function ($url, $model) {
                        if (User::can('role-menu', 'delete'))
                            return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']).' ';
                    },
                ]
            ],
        ],
    ]); ?>
</div>