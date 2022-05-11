<?php
use yii\helpers\Html;

$this->title = 'Update Navbar: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Navbar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>