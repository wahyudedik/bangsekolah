<?php

use yii\helpers\Html;
use common\helpers\Strings;
use yii\grid\GridView;

$this->title = 'Tagihan '.$student->name;
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $student->name;
\yii\web\YiiAsset::register($this);
?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/index'], ['class' => 'btn btn-warning']) ?>
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus"></i> Buat tagihan', ['invoice/create-single', 'student' => $student->id], ['class' => 'btn btn-success']) ?>
                </div>
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
                            [
                                'label' => 'Bulan',
                                'value' => function($model) {
                                    return $model->month ? $model->month : '';
                                }
                            ],
                            // ['attribute' => 'year'],
                            ['attribute' => 'amountRupiah'],
                            ['attribute' => 'paidAmountRupiah'],
                            [
                                'label' => 'Sisa Bayar',
                                'value' => function($model){
                                    return Strings::getRupiah($model->amount - $model->paid_amount);
                                }
                            ],
                            [
                                'label' => 'Jatuh Tempo',
                                'value' => function($model){
                                    return date('d M Y', strtotime($model->due_date));
                                }
                            ],
                            ['attribute' => 'statusLabel', 'format' => 'raw'],
                            
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Detail',
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