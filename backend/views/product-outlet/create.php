<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductOutlet */

$this->title = 'Create Product Outlet';
$this->params['breadcrumbs'][] = ['label' => 'Product Outlets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-outlet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
