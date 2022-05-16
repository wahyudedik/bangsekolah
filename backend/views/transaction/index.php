<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus fa-lg"></i> Transaksi Pembayaran Baru', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
        <div class="input-group input-group-sm">
            <span for="startdate" style="margin-right: 5px">From: </span>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <input type="date" name="startdate" class="form-control" value="<?= $startdate ?>">
            </div>
            <span for="enddate" style="margin-right: 5px">To: </span>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <input type="date" name="enddate" class="form-control" value="<?= $enddate ?>">
            </div>

            <input type="text" name="no" class="form-control" placeholder="Cari nomor transaksi" value="<?=   $no ?>">
            <span style="padding-left: 5px"></span>
            <input type="text" name="nisn" class="form-control" placeholder="Cari NISN" value="<?=   $nisn ?>">
            <span style="padding-left: 5px"></span>
            <input type="text" name="name" class="form-control" placeholder="Cari nama" value="<?=   $name ?>">
            <span style="padding-left: 5px"></span>
            <select class="form-control" name="type">
                <option <?= $type == 'ALL' ? 'selected="selected"' : '' ?> value="ALL">Semua Pembayaran</option>
                <?php 
                foreach ($payment_types as $payment_type) {
                    echo '<option '.($payment_type->name == $type ? 'selected' : '').' value="'.$payment_type->name.'">'.$payment_type->name.'</option>';
                }
                ?>
            </select>


            <span class="input-group-append" style="padding-left: 5px">
                <?= Html::submitButton('<i class="fas fa-search search-icon"></i>', ['class' => 'btn btn-info btn-flat']) ?>
            </span>
        </div>
        <?php ActiveForm::end() ?>
    </div>
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
                    ['attribute' => 'number', 'label' => 'Nomor Transaksi'],
                    ['attribute' => 'date', 'label' => 'Tanggal'],
                    ['attribute' => 'student.nisn', 'label' => 'NISN'],
                    ['attribute' => 'student.name', 'label' => 'Nama Siswa'],
                    ['attribute' => 'student.class', 'label' => 'Kelas'],
                    ['attribute' => 'invoice.description', 'label' => 'Nama Pembayaran'],
                    ['attribute' => 'amountRupiah', 'label' => 'Jumlah Bayar'],
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view}{update}{delete}', 
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