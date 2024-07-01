<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = 'Tambah Post Category';
$this->params['breadcrumbs'][] = ['label' => 'Post Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
