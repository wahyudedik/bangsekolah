<?php
use yii\helpers\Html;

$this->title = 'Update: '.$model->menu->name;
$this->params['breadcrumbs'][] = ['label' => 'Menu '.$model->role->name, 'url' => ['role-menu/index', 'role' => $model->role->id]];
$this->params['breadcrumbs'][] = ['label' => $model->menu->name, 'url' => ['role-menu/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
    'model' => $model,
    'role' => $model->role,
    'arrData' => $arrData,
    'menu' => $menu,
]) ?>