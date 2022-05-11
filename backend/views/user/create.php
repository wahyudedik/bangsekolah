<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Create User';
if($school_id) {
    $this->params['breadcrumbs'][] = ['label' => 'Schools', 'url' => ['school/index']];
    $this->params['breadcrumbs'][] = ['label' => $school->name, 'url' => ['school/view', 'id' => $school_id]];
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>

<?= $this->render('_form_create', [
    'model' => $model,
    'school_id' => $school_id,
]) ?>