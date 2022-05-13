<?php
use yii\helpers\Html;

$this->title = 'Tambah Kelas';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-class-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
