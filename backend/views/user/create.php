<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Create User';
if($outlet_id) {
    $this->params['breadcrumbs'][] = ['label' => 'Outlets', 'url' => ['outlet/index']];
    $this->params['breadcrumbs'][] = ['label' => $outlet->name, 'url' => ['outlet/view', 'id' => $outlet_id]];
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>

<?= $this->render('_form_create', [
    'model' => $model,
    'outlet_id' => $outlet_id,
]) ?>