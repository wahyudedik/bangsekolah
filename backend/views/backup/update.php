<?php
use yii\helpers\Html;

$this->title = 'Update Backup: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Backups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="backup-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
