<?php
use yii\helpers\Html;

$this->title = 'Tambah Pembayaran';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pembayaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-class-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
