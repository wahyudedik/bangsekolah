<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\helpers\Strings;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;

$total = 0;
foreach ($dataProvider->models as $model) {
    $total += $model->total;
}
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-9">
            </div>
            <div class="col-lg-3">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                    <?= Html::a('<i class="fas fa-download fa-lg"></i> Download', ['export', 'startdate' => $startdate, 'enddate' => $enddate, 'outlet' => $outlet, 'status' => $status], ['class' => 'btn btn-primary']) ?>
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
                <select class="form-control" name="outlet">
                    <option <?= $outlet == 'ALL' ? 'selected="selected"' : '' ?> value="ALL">All Outlet</option>
                    <?php 
                    foreach ($outlets as $otl) {
                        echo '<option '.($otl->name == $outlet ? 'selected' : '').' value="'.$otl->name.'">'.$otl->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <select class="form-control" name="status" id="select-status">
                    <option <?= $status == 'ALL' ? 'selected="selected"' : '' ?> value="ALL">All Status</option>
                    <option <?= $status == 'ORDER' ? 'selected="selected"' : '' ?> value="ORDER">ORDER</option>
                    <option <?= $status == 'FINALIZE' ? 'selected="selected"' : '' ?> value="FINALIZE">FINALIZE</option>
                    <option <?= $status == 'SENT' ? 'selected="selected"' : '' ?> value="SENT">SENT</option>
                    <option <?= $status == 'PAID' ? 'selected="selected"' : '' ?> value="PAID">PAID</option>
                </select>
            </div>
            <div class="input-group input-group-sm" style="margin-right: 5px;">
                <input type="text" name="q" class="form-control" placeholder="Order number ..." value="<?=   $q ?>">
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

                    //'id',
                    'order_number',
                    'invoice_number',
                    ['attribute' => 'outlet.name', 'label' => 'Outlet'],
                    'orderedAt',
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
            <table class="table table-bordered">
                <tr>
                    <th>TOTAL</th>
                    <th><h4 style="margin-bottom: 0px;"><?= Strings::getRupiah($total) ?></h4></th>
                </tr>
            </table>
        </div>
    </div>
</div>