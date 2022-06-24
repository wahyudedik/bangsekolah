<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengguna';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="form-control" placeholder="Cari nama pengguna ..." value="<?=  $q ?>">
                            <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                            </span>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-tools float-right">
                            <?php
                            if (User::can('role', 'create'))
                                // echo Html::a('<i class="fas fa-plus-circle fa-lg"></i> Create User', ['create'], ['class' => 'btn btn-success pull-right']);
                            ?>
                            <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                    <?= Html::a('<i class="fas fa-download fa-lg"></i> Download', ['index'], ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => [
                                'class' => 'table table-striped table-hover'
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'name',
                                'username',
                                ['attribute' => 'role.name', 'format' => 'raw', 'label' => 'Role'],
                                'email:email',
                                ['attribute' => 'created_at', 'label' => 'Terdaftar Pada'],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Action',
                                    'template' => '{view}{update}{delete}', 
                                    'contentOptions' => ['style' => 'width:150px;'],
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            if (User::can('user', 'view')) {
                                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' ';
                                            }
                                        },
                                        'update' => function ($url, $model) {
                                            if (User::can('user', 'update')) {
                                                return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' ';
                                            }
                                        },
                                        'delete' => function ($url, $model) { 
                                            if (User::can('user', 'delete')) {
                                                return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']).' ';
                                            }
                                        },
                                    ]
                                ],
                            ],
                        ]); ?>
                </div>
                <?= LinkPager::widget(['pagination'=>$dataProvider->pagination]) ?>
            </div>
        </div>
    </div>
</div>