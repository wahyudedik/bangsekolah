<?php
use yii\helpers\Html;

$this->title = 'Update Bulan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Bulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="school-class-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
