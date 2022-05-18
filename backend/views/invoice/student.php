<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tagihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Cari NISN atau nama siswa ..." value="<?=   $q ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus fa-lg"></i> Buat Tagihan', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
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

                    'nisn',
                    'name',
                    'class',
                    'year',
                    [
                        'label' => 'Tagihan',
                        'format' => 'raw',
                        'value' => function($model) {
                            $paid = 0;
                            $inv = 0;
                            foreach ($model->invoices as $invoice) {
                                $inv++;
                                if ($invoice->status == 'PAID') {
                                    $paid++;
                                }
                            }
                            if ($inv == $paid) {
                                return '<span class="text-success">'.$paid.'/'.$inv.'</span>';
                            } else {
                                return '<span class="text-danger">'.$paid.'/'.$inv.'</span>';
                            }
                        }
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Detail',
                        'template' => '{view}', 
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i> Lihat Tagihan', ['invoice/invoices', 'student' => $model->id], ['class' => 'btn btn-success']).' ';
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
