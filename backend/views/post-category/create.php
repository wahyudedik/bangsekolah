<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PostCategory */

$this->title = 'Create Post Category';
$this->params['breadcrumbs'][] = ['label' => 'Post Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>