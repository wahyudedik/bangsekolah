<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Daftar Tagihan '.$student->name;
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $student->name;
\yii\web\YiiAsset::register($this);
?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/index'], ['class' => 'btn btn-warning']) ?>
            </header>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary'=> '',
                        'tableOptions' => [
                            'class' => 'table table-striped table-hover'
                        ],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                                //'id',
                            ['attribute' => 'number'],
                            ['attribute' => 'description'],
                            ['attribute' => 'month'],
                            ['attribute' => 'year'],
                            ['attribute' => 'amount'],
                            ['attribute' => 'paid_amount'],
                            ['attribute' => 'status'],
                            
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{view}', 
                                'contentOptions' => ['style' => 'width:150px;'],
                                'buttons' => [
                                    'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                                    'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                                    'delete' => function ($url, $model) {
                                        return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']).' ';
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>  
</div>