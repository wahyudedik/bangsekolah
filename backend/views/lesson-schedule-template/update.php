<?php
use yii\helpers\Html;

$this->title = 'Update Lesson Schedule Template: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Pelajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-schedule-template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
