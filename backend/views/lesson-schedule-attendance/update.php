<?php
use yii\helpers\Html;

$this->title = 'Update Lesson Schedule Attendance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Schedule Attendances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-schedule-attendance-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
