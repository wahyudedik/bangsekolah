<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserSchool */

$this->title = 'Edit User: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Daftar User', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $user_school->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-school-update">

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
