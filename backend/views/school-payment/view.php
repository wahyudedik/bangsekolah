<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Pembayaran '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pembayaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['school-payment/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Apakah Anda yakin hapus data ini?");',
                    ]) ?>
                </div>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table table-striped table-bordered detail-view'
                    ],
                    'attributes' => [
                    // 'id',
                    'name',
                    'number',
                    // 'year',
                    ['attribute' => 'paymentType.name', 'label' => 'Jenis Pembayaran'],
                    // ['attribute' => 'school.name', 'label' => 'Sekolah'],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>