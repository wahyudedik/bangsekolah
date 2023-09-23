<?php
use yii\helpers\Html;

$this->title = 'Tambah Hari Efektif';
$this->params['breadcrumbs'][] = ['label' => 'Hari Efektif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="effective-day-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
