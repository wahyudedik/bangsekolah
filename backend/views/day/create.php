<?php
use yii\helpers\Html;

$this->title = 'Tambah Hari';
$this->params['breadcrumbs'][] = ['label' => 'Hari', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="effective-day-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
