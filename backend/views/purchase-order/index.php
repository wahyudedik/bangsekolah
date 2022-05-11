<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-9">
                
            </div>
            <div class="col-lg-3">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                    <?= Html::a('<i class="fas fa-download fa-lg"></i> Download', ['export', 'q' => $q, 'supplier' => $supplier, 'startdate' => $startdate, 'enddate' => $enddate, 'status' => $status], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-2">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
            <span for="startdate" style="margin-right: 5px">From: </span>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <input type="date" name="startdate" class="form-control" value="<?= $startdate ?>">
            </div>
            <span for="enddate" style="margin-right: 5px">To: </span>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <input type="date" name="enddate" class="form-control" value="<?= $enddate ?>">
            </div>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <select class="form-control" name="supplier">
                    <option <?= $supplier == 'ALL' ? 'selected="selected"' : '' ?> value="ALL">All Supplier</option>
                    <?php 
                    foreach ($suppliers as $sup) {
                        echo '<option '.($sup->name == $supplier ? 'selected' : '').' value="'.$sup->name.'">'.$sup->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <select class="form-control" name="status" id="select-status">
                    <option <?= $status == 'ALL' ? 'selected="selected"' : '' ?> value="ALL">All Status</option>
                    <option <?= $status == 'DRAFT' ? 'selected="selected"' : '' ?> value="DRAFT">DRAFT</option>
                    <option <?= $status == 'PROCESS' ? 'selected="selected"' : '' ?> value="PROCESS">PROCESS</option>
                    <option <?= $status == 'FINISHED' ? 'selected="selected"' : '' ?> value="FINISHED">FINISHED</option>
                </select>
            </div>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <input type="text" name="q" class="form-control" placeholder="PO Number ..." value="<?=   $q ?>">
            </div>
            <?= Html::submitButton('<i class="fas fa-search search-icon"></i> Apply', ['class' => 'btn btn-info']) ?>
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

                    'po_number',
                    'createdAt',
                    ['attribute' => 'supplier.name', 'label' => 'Supplier Name'],
                    'totalRupiah',
                    'status',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view}', 
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
