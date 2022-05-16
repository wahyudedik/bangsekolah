<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->invoice->description;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['transaction/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
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
                        ['attribute' => 'date', 'label' => 'Tanggal'],
                        ['attribute' => 'student.nisn', 'label' => 'NISN'],
                        ['attribute' => 'student.name', 'label' => 'Nama Siswa'],
                        ['attribute' => 'invoice.description', 'label' => 'Nama Pembayaran'],
                        ['attribute' => 'amountRupiah', 'label' => 'Jumlah Bayar'],
                        ['attribute' => 'user.name', 'label' => 'Admin'],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>