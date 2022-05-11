<?php

use yii\helpers\Html;
use yii\widgets\GridView;

$this->title = $model->so_number;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->, 'url' => ['index']];
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'stock_opname_id',
                        'product_id',
                        'stock_product',
                        'stock_opname',
                        //'status',
                        //'checked_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                
            </div>
        </div>
    </div>
</div>
