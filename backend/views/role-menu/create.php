<?php
use yii\helpers\Html;

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Menu '.$role->name, 'url' => ['role-menu/index', 'role' => $role->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'role' => $role,
    'arrData' => $arrData,
]) ?>