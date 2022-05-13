<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = 'Edit Tagihan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tagihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
