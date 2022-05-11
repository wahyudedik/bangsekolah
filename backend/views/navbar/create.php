<?php
use yii\helpers\Html;

$this->title = 'Create Navbar';
$this->params['breadcrumbs'][] = ['label' => 'Navbar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>