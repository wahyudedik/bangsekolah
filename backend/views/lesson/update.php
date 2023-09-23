<?php
use yii\helpers\Html;

$this->title = 'Update Pelajaran: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pelajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
