<?php
use yii\helpers\Html;

$this->title = 'Create Holiday';
$this->params['breadcrumbs'][] = ['label' => 'Hari Libur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
