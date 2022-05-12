<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SchoolMonth */

$this->title = 'Tambah Bulan Sekolah';
$this->params['breadcrumbs'][] = ['label' => 'Bulan Sekolah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-month-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
