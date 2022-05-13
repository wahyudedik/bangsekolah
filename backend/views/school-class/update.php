<?php
use yii\helpers\Html;

$this->title = 'Update Kelas: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="school-class-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
