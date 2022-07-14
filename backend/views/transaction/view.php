<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->invoice->description;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['transaction/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-times"></i> Batalkan', ['delete', 'number' => $model->number], ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Yakin ingin hapus transaksi ini?");']) ?>
                    <?= Html::a('<i class="fas fa-print"></i> Bukti Pembayaran', ['print', 'id' => $model->id], ['class' => 'btn btn-info', 'target' => '_blank']) ?>
                </div>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table table-striped table-bordered detail-view'
                    ],
                    'attributes' => [
                        ['attribute' => 'number', 'label' => 'Nomor Transaksi'],
                        [
                            'label' => 'Nomor Invoice',
                            'format' => 'raw',
                            'value' => function($model){
                                return Html::a($model->invoice->number, ['invoice/view', 'id' => $model->invoice->id]);
                            }
                        ],
                        [
                            'label' => 'Tanggal Bayar',
                            'value' => function($model){
                                return date('d M Y', strtotime($model->date));
                            }
                        ],
                        ['attribute' => 'student.nis', 'label' => 'NIS'],
                        ['attribute' => 'student.name', 'label' => 'Nama Siswa'],
                        ['attribute' => 'invoice.description', 'label' => 'Nama Pembayaran'],
                        ['attribute' => 'amountRupiah', 'label' => 'Jumlah Bayar'],

                        ['attribute' => 'user.name', 'label' => 'Admin'],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h5>Transaksi <?= $model->number ?></h5>
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

                            ['attribute' => 'invoice.description', 'label' => 'Nama Pembayaran'],
                            ['attribute' => 'invoice.month', 'label' => 'Bulan'],
                            ['attribute' => 'amountRupiah', 'label' => 'Jumlah Bayar'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>