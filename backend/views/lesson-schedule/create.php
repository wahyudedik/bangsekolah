<?php
use yii\helpers\Html;

$this->title = 'Create Lesson Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-schedule-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
