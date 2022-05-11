<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Outlet */

$this->title = 'Create Outlet';
$this->params['breadcrumbs'][] = ['label' => 'Outlets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
