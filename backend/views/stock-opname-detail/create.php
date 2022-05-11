<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StockOpnameDetail */

$this->title = 'Create Stock Opname Detail';
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
