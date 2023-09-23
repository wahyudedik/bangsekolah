<?php
use yii\helpers\Html;

$this->title = 'Tambah Jadwal Pelajaran';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Pelajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-schedule-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
