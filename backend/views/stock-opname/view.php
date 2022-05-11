<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->title = $model->so_number;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['production/index'], ['class' => 'btn btn-warning']) ?>
                <span class="float-right">
                    <span class="badge badge-success"><?= $model->status ?></span>
                </span>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table table-striped table-bordered detail-view'
                    ],
                    'attributes' => [
                        //'id',
                        'so_number',
                        'productCount',
                        'createdAt',
                        ['attribute' => 'createdBy.name', 'label' => 'Checked By' ],
                    ],
                ]) ?>
                
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card card-secondary card-outline card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="tab-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#tab-detail" role="tab" aria-controls="tab-detail" aria-selected="true">
                            PRODUCT LIST
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab-item" role="tab" aria-controls="tab-item" aria-selected="">
                            ITEM
                        </a>
                    </li> -->
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tab-tabContent">
                    <?= $this->render('_tab-stock-opname-detail', [
                        'model' => $model,
                    ]) ?>
                    
                    <?php /*= $this->render('_tab-item', [
                        'model' => $model,
                        'detail_opnames' => $detail_opnames,
                    ])*/ ?>
                </div>
            </div>
        </div>
    </div>
</div>
