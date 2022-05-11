<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Outlet */

$this->title = 'Update Outlet: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Outlets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
