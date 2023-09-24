<?php
use yii\helpers\Html;

$this->title = 'Create Lesson Schedule Template';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Pelajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-schedule-template-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
