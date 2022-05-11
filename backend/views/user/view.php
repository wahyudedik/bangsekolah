<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pengguna */

$this->title = $model->name;
if (Yii::$app->user->identity->id == $model->id && !Yii::$app->request->get('id')) {
    $this->params['breadcrumbs'][] = 'Profile';
} else {
    $this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}    
?>

<div class="row" id="user-profile">
    <div class="col-lg-3 col-md-4 col-sm-4">
        <?= $this->render('@app/views/user/_profile_box', ['model' => $model]) ?>
    </div>
    
    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <h2 class="filter-block float-left">Details</h2>
            </header>
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    ['attribute' => 'role.name', 'format' => 'raw', 'label' => 'Role'],
                    'username',
                    'name',
                    'email:email',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>
</div>