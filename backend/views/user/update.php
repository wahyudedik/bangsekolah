<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pengguna */

$this->title = 'Update User: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form_update', [
    'model' => $model,
    'school_id' => $school_id,
]) ?>