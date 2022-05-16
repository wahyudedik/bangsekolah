<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->description.' '.$model->month;
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student->name, 'url' => ['invoice/invoices', 'student' => $model->student->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/invoices', 'student' => $model->student->id], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="nav-icon fas fa-hand-holding-usd"></i> Bayar', ['transaction/create', 'nisn' => $model->student->nisn], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="nav-icon fas fa-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Yakin hapus data ini?");',
                    ]) ?>
                </div>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'number',
                        ['attribute' => 'student.name', 'label' => 'Diberikan Kepada'],
                        'description:ntext',
                        'month',
                        'year',
                        'amountRupiah',
                        'paidAmountRupiah',
                        'date',
                        'due_date',
                        ['attribute' => 'statusLabel', 'format' => 'raw'],
                        ['attribute' => 'user.name', 'label' => 'Pembuat'],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <header class="card-header">
                <h5>Riwayat Pembayaran</h5>
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

                            ['attribute' => 'date', 'label' => 'Tanggal'],
                            ['attribute' => 'amountRupiah', 'label' => 'Jumlah Bayar'],
                            // ['attribute' => 'user.name', 'label' => 'Admin'],
                            
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{view}', 
                                'contentOptions' => ['style' => 'width:150px;'],
                                'buttons' => [
                                    'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', ['transaction/view', 'id' => $model->id], ['class' => 'btn btn-success']).' '; },
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>  
</div>