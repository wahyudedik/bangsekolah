<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = $model->description.' '.$model->month;
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student->name, 'url' => ['invoice/invoices', 'student' => $model->student->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">

    <div class="col-lg-6 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['invoice/invoices', 'student' => $model->student->id], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?php //= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Hapus', ['delete', 'id' => $model->id], [
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
                    'description:ntext',
                    'month',
                    'year',
                    'amount',
                    'paid_amount',
                    'date',
                    'due_date',
                    'status',
                    ['attribute' => 'user.name', 'label' => 'User'],
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>