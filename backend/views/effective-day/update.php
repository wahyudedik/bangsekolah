<?php
use yii\helpers\Html;

$this->title = 'Update Hari Efektif: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hari Efektif', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="effective-day-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
