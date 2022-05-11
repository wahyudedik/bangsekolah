<?php
use yii\helpers\Html;

$this->title = 'Create Setting';
$this->params['breadcrumbs'][] = ['label' => 'Setting', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>