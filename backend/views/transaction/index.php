<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\School;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;

$school = School::findOne(Yii::$app->session->get('school_id'));
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
    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <label>Rentang Tanggal</label><br>
                    <div class="input-group input-group-sm">
                        <input type="date" class="form-control" name="startdate" value="<?= $startdate ?>">
                        <input type="date" class="form-control" name="enddate" value="<?= $enddate ?>">                    
                    </div>
                </div>
                <div class="col-lg-2">
                    <label>Kelas</label><br>
                    <div class="input-group input-group-sm">
                        <select class="form-control" name="class">
                            <option value="">Semua</option>
                            <?php 
                            foreach ($schoolClasses as $cls) {
                                echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <label>NIS</label><br>
                    <div class="input-group input-group-sm">
                        <input type="text" name="nis" class="form-control" value="<?= $nis ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <label>Nama Siswa</label><br>
                    <div class="input-group input-group-sm">
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <label>Pembayaran</label><br>
                    <div class="input-group input-group-sm">
                        <select class="form-control" name="payment">
                            <option value="">Semua</option>
                            <?php 
                            foreach ($schoolPayments as $pymnt) {
                                echo '<option '.($pymnt->name == $payment ? 'selected' : '').' value="'.$pymnt->name.'">'.$pymnt->name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header">
            <?= Html::submitButton('<i class="fas fa-search"></i> Terapkan', ['class' => 'btn btn-success']) ?>
            <?php if (count($school->students)>0): ?>
                <?= Html::a('<i class="fas fa-upload"></i> Import', ['import'], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
            <?= Html::a('<i class="fas fa-sync-alt"></i> Reset', ['transaction/index'], ['class' => 'btn btn-primary']) ?>
        </div>        
    <?php ActiveForm::end() ?>
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
                    [
                        'label' => 'Tanggal',
                        'value' => function($model) {
                            return date('d/m/Y', strtotime($model->date));
                        }
                    ],
                    ['attribute' => 'student.name', 'label' => 'Nama Siswa'],
                    ['attribute' => 'student.class', 'label' => 'Kelas'],
                    ['attribute' => 'invoice.description', 'label' => 'Nama Pembayaran'],
                    ['attribute' => 'amountRupiah', 'label' => 'Jumlah Bayar'],
                    ['attribute' => 'user.name', 'label' => 'Petugas'],
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Detail',
                        'template' => '{view}{print}', 
                        'contentOptions' => ['style' => 'width:100px;'],
                        'buttons' => [
                            'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                            'print' => function ($url, $model) { return Html::a('<i class="fas fa-print"></i>', ['transaction/print', 'id' => $model->id], ['class' => 'btn btn-info', 'target' => '_blank']).' '; },
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