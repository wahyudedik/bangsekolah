<?php

use yii\helpers\Html;

$this->title = 'Tambah Jadwal Pelajaran';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Pelajaran', 'url' => ['index', 'class' => $class]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-schedule-template-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>