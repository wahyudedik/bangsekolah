<?php
use yii\helpers\Html;

$this->title = 'Tambah Bulan';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Bulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-class-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
