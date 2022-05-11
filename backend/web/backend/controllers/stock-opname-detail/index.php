<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stock Opname Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Stock Opname Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
