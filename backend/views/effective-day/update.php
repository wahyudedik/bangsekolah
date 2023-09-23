<?php
use yii\helpers\Html;

$this->title = 'Update Effective Day: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Effective Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="effective-day-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
