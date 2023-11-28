<?php
use yii\helpers\Html;

$this->title = 'Create Presence';
$this->params['breadcrumbs'][] = ['label' => 'Presences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presence-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
