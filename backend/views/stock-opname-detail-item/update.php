<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StockOpnameDetailItem */

$this->title = 'Update Stock Opname Detail Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname Detail Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-opname-detail-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
