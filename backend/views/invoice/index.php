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
                    <input type="text" name="q" class="form-control" placeholder="Cari ..." value="<?=   $q ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus fa-lg"></i> Tambah Tagihan', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-upload fa-lg"></i> Import', ['import'], ['class' => 'btn btn-success']) ?>
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
                    //'school_id',
                    //'student_id',
                    ['attribute' => 'student.name', 'label' => 'Name'],
                    ['attribute' => 'schoolPayment.name', 'label' => 'Payment Type'],
                    'description:ntext',
                    //'school_payment_id',
                    'number',
                    'year',
                    'month',
                    //'amount',
                    //'paid_amount',
                    //'due_date',
                    'status',
                    //'user_id',
                    //'created_at',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view}{update}{delete}', 
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                            'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Yakin untuk menghapus ini?");']).' ';
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
