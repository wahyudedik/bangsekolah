<?php
use yii\helpers\Html;

$this->title = 'Tambah Pelajaran';
$this->params['breadcrumbs'][] = ['label' => 'Pelajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
