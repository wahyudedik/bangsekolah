<?php
use yii\helpers\Html;

$this->title = 'Create Lesson';
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
