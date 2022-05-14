<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">

    <div class="col-lg-6 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php //Html::a('<i class="fas fa-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                            //'class' => 'btn btn-danger',
                            //'onclick' => 'return confirm("Yakin untuk menghapus ini?");',
                    //]) ?>
                </div>
            </header>
            
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    //'school_id',
                    //'student_id',
                    ['attribute' => 'student.nis', 'label' => 'NIS'],
                    ['attribute' => 'student.nisn', 'label' => 'NISN'],
                    ['attribute' => 'student.name', 'label' => 'Nama Siswa'],
                    ['attribute' => 'schoolPayment.name', 'label' => 'Pembayaran'],
                    //'school_payment_id',
                    'number',
                    'year',
                    'month',
                    'description:ntext',
                    'amount',
                    'paid_amount',
                    'date',
                    'due_date',
                    'status',
                    ['attribute' => 'user.name', 'label' => 'User'],
                    //'user_id',
                    //'created_at',
                    //'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>