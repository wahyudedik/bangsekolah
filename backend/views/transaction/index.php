<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <!-- <div class="card-header">
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
    </div> -->
    <div class="card-body">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
        <div class="row">
            <div class="col-lg-2">
                <label>Rentang Tanggal</label><br>
                <input type="date" class="form-control" name="startdate" value="<?= $startdate ?>">
                <input type="date" class="form-control" name="enddate" value="<?= $enddate ?>">
            </div>
            <div class="col-lg-2">
                <label>Kelas</label><br>
                <select class="form-control" name="class">
                    <option value="">Semua</option>
                    <?php 
                    foreach ($schoolClasses as $cls) {
                        echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2">
                <label>NISN</label><br>
                <input type="text" name="nisn" class="form-control" value="<?= $nisn ?>">
            </div>
            <div class="col-lg-2">
                <label>Nama Siswa</label><br>
                <input type="text" name="name" class="form-control" value="<?= $name ?>">
            </div>
            <div class="col-lg-2">
                <label>Pembayaran</label><br>
                <select class="form-control" name="payment">
                    <option value="">Semua</option>
                    <?php 
                    foreach ($schoolPayments as $pymnt) {
                        echo '<option '.($pymnt->name == $payment ? 'selected' : '').' value="'.$pymnt->name.'">'.$pymnt->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2">
                <br>
                <?= Html::submitButton('<i class="fas fa-search"></i> Terapkan', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-upload"></i> Import', ['import'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-sync-alt"></i> Reset', ['transaction/index'], ['class' => 'btn btn-primary']) ?>
            </div>
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
                        'header' => 'Detail',
                        'template' => '{view}', 
                        // 'contentOptions' => ['style' => 'width:150px;'],
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