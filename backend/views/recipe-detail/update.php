<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RecipeDetail */

$this->title = 'Update Ingredients';
$this->params['breadcrumbs'][] = ['label' => 'Recipes', 'url' => ['recipe/index']];
$this->params['breadcrumbs'][] = ['label' => $model->recipe->name, 'url' => ['recipe/view', 'id' => $model->recipe->id]];
$this->params['breadcrumbs'][] = ['label' => 'Ingredients', 'url' => ['recipe-detail/index', 'id' => $model->recipe->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-detail-update">

    <?= $this->render('_form', [
        'model' => $model,
        'recipe' => $model->recipe,
    ]) ?>

</div>
