<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserSchool */

$this->title = 'Tambah User';
$this->params['breadcrumbs'][] = ['label' => 'Daftar User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-school-create">

    <?= $this->render('_form_create', [
        'model' => $model,
    ]) ?>

</div>
