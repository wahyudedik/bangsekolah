<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Daftar Pembayaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari pembayaran ..." value="<?= $search ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus fa-lg"></i> Tambah Pembayaran', ['create'], ['class' => 'btn btn-success']) ?>
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

                        //'id',
                    'name',
                    'number',
                    // 'year',
                    ['attribute' => 'paymentType.label', 'label' => 'Jenis Pembayaran'],
                    ['attribute' => 'paymentType.description', 'label' => 'Deskripsi'],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Detail',
                            'template' => '{view}', 
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