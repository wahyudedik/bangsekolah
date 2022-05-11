<?php
use yii\helpers\Html;

$this->title = 'Add New Subscriber';
$this->params['breadcrumbs'][] = ['label' => 'Subscriber', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>