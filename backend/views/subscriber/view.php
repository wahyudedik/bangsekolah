<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

$this->title = 'Detail Subscriber';
$this->params['breadcrumbs'][] = ['label' => 'Role', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h4><?= $model->email ?></h4>
            </header>
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>