<?php
use yii\helpers\Html;

$this->title = 'Create Lesson Schedule Attendance';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Schedule Attendances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-schedule-attendance-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
