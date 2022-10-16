<?php
use yii\helpers\Html;

$this->title = 'Create Backup';
$this->params['breadcrumbs'][] = ['label' => 'Backups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backup-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
