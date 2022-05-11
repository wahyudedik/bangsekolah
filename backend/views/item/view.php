<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['product/index']];
$this->params['breadcrumbs'][] = ['label' => $model->product->name, 'url' => ['product/view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-6">
        <?php
        if ($model->status == 'ACTIVE') {
            echo '<div class="alert alert-success">THIS ITEM IS ACTIVE</div>';
        } else {
            echo '<div class="alert alert-danger">THIS ITEM IS NON ACTIVE</div>';
        }
        ?>
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['product/view', 'id' => $model->product->id], ['class' => 'btn btn-warning']) ?>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'code',
                        ['attribute' => 'product.name'],
                        ['attribute' => 'name', 'label' => 'Item Name'],
                        'product_code',
                        [
                            'label' => 'Base Amount',
                            'value' => function($model) {
                                return $model->base_amount.' '.$model->unit;
                            }
                        ],
                        [
                            'label' => 'Remaining Amount',
                            'value' => function($model) {
                                return $model->amount.' '.$model->unit;
                            }
                        ],
                        ['attribute' => 'production.production_number'],
                        'createdAt',
                        ['attribute' => 'createdBy.name', 'label' => 'Created By'],
                        [
                            'label' => 'QR Code',
                            'value' => function($model) {
                                if ($model->status =='ACTIVE') {
                                    return Html::img('https://api.qrserver.com/v1/create-qr-code/?size=100x100&data='.$model->code);
                                } else {
                                    return 'QR disabled';
                                }
                            },
                            'format' => 'raw'
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-secondary card-outline card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="tab-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#tab-info" aria-controls="tab-detail" aria-selected="true"><?= $model->production_id ? 'PRODUCTION' : 'PURCHASE' ?> INFO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab-usage">USAGE</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tab-tabContent">
                    <?= $this->render('_tab-info', [
                        'model' => $model,
                    ]) ?>
                    <?= $this->render('_tab-usage', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
