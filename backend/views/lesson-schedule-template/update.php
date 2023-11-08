<?php

use yii\helpers\Html;

$title = $model->class . ' ' . $model->day . ' Jam ' . $model->hour;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Pelajaran', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $title;
?>
<div class="lesson-schedule-template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>