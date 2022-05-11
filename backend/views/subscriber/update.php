<?php
use yii\helpers\Html;

$this->title = 'Update Subscriber: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Subscriber', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>