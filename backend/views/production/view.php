<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Production */

$this->title = $model->production_number;
$this->params['breadcrumbs'][] = ['label' => 'Productions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
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
                        ['attribute' => 'recipe.name', 'label' => 'Recipe'],
                        ['attribute' => 'product.name', 'label' => 'Product'],
                        'serving',
                        'quantity',
                        'note:ntext',
                        'status',
                        'createdAt',
                        ['attribute' => 'createdBy.name', 'label' => 'Created By'],
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
                        <a class="nav-link active" id="tab-product-tab" data-toggle="pill" href="#tab-product" role="tab" aria-controls="tab-sender" aria-selected="true">
                        RAW MATERIALS
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-item-tab" data-toggle="pill" href="#tab-item" role="tab" aria-controls="tab-detail" aria-selected="">
                        OUTPUT PRODUCTION ITEM
                    </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tab-tabContent">
                    <?= $this->render('_tab-product', [
                        'model' => $model,
                    ]) ?>
                    <?= $this->render('_tab-item', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
