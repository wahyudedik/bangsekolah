<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StockOpnameDetailItem */

$this->title = 'Create Stock Opname Detail Item';
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname Detail Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-detail-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
