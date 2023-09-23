<?php
use yii\helpers\Html;

$this->title = 'Create Effective Day';
$this->params['breadcrumbs'][] = ['label' => 'Effective Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="effective-day-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
