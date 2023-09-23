<?php
use yii\helpers\Html;

$this->title = 'Update Holiday: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Holidays', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="holiday-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
