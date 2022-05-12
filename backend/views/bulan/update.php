<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SchoolMonth */

$this->title = 'Edit Bulan Sekolah: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bulan Sekolah', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="school-month-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
