<?php
use yii\helpers\Html;

$this->title = 'Update Presence: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Presences', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="presence-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
