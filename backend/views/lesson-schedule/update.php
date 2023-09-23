<?php
use yii\helpers\Html;

$this->title = 'Update Lesson Schedule: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-schedule-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
