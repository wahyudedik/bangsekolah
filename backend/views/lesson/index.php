<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Pelajaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Search ..." value="<?= $q ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?php if (Yii::$app->user->identity->role_id == 5) : ?>
                        <?= Html::a('<i class="fas fa-plus fa-lg"></i> Tambah Pelajaran', ['create'], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '',
                'tableOptions' => [
                    'class' => 'table table-striped table-hover'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    // 'major',
                    'name',
                    // 'status',
                    //'created_at',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view}{update}{delete}',
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']) . ' ';
                            },
                            'update' => function ($url, $model) {
                                if (Yii::$app->user->identity->role_id == 2) {
                                    return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']) . ' ';
                                }
                            },
                            'delete' => function ($url, $model) {
                                if (Yii::$app->user->identity->role_id == 5 && count($model->lessonScheduleTemplates) == 0) {
                                    return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']) . ' ';
                                }
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>