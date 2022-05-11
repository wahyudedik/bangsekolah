<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RecipeDetail */

$this->title = 'Add Raw Material';
$this->params['breadcrumbs'][] = ['label' => 'Recipe', 'url' => ['recipe/index']];
$this->params['breadcrumbs'][] = ['label' => $recipe->name, 'url' => ['recipe/view', 'id' => $recipe->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-detail-create">

    <?= $this->render('_form', [
        'model' => $model,
        'recipe' => $recipe,
    ]) ?>

</div>
