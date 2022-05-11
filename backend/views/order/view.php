<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->order_number;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['order/index'], ['class' => 'btn btn-warning']) ?>
                <span class="float-right">
                    <span class="badge badge-success"><?= $model->status ?></span>
                </span>
                <!-- <div class="filter-block">
                    <h5>Order Detail</h5>
                </div> -->
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'order_number',
                        'invoice_number',
                        'note',
                        'seller_note',
                        [
                            'label' => 'Ordered At',
                            'value' => function($model) {
                                return $model->ordered_at !== null ? date('d M Y H:i:s', strtotime($model->ordered_at)) : '';
                            }
                        ],
                        [
                            'label' => 'Finalized At',
                            'value' => function($model) {
                                return $model->finalized_at !== null ? date('d M Y H:i:s', strtotime($model->finalized_at)) : '';
                            }
                        ],
                        [
                            'label' => 'Paid At',
                            'value' => function($model) {
                                return $model->paid_at !== null ? date('d M Y H:i:s', strtotime($model->paid_at)) : '';
                            }
                        ],
                        ['label' => 'Created By', 'attribute' => 'createdBy.name'],
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
                        <a class="nav-link active" data-toggle="pill" href="#tab-detail" role="tab" aria-controls="tab-detail" aria-selected="true">
                            PRODUCT LIST
                        </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-item" role="tab" aria-controls="tab-item" aria-selected="">
                            PACKED ITEMS
                        </a>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-invoice" role="tab" aria-controls="tab-invoice" aria-selected="">
                            INVOICE & PAYMENT
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tab-tabContent">
                    <?= $this->render('_tab-order-detail', [
                        'model' => $model,
                    ]) ?>

                    <?= $this->render('_tab-item', [
                        'model' => $model,
                    ]) ?>

                    <?= $this->render('_tab-invoice', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
