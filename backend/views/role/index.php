<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\User;

$this->title = 'Role';
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
                            <input type="text" name="q" class="form-control" placeholder="Search ..." value="<?=  $q ?>">
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
                                echo Html::a('<i class="fas fa-plus-circle fa-lg"></i> Create Role', ['create'], ['class' => 'btn btn-success pull-right']);
                            ?>
                            <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
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
                            'class' => 'table table-hover'
                        ],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                                // 'id',
                                'name',
                                'description:ntext',
                                ['attribute' => 'linkMenu', 'format' => 'raw', 'label' => 'Menu Count'],
                                // 'status',
                                'created_at',
                                // 'updated_at',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{view}{update}{delete}', 
                                'contentOptions' => ['style' => 'width:150px;'],
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        if (User::can('role', 'view'))
                                            return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' ';
                                    },
                                    'update' => function ($url, $model) {
                                        if (User::can('role', 'update'))
                                            return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' ';
                                    },
                                    'delete' => function ($url, $model) {
                                        if (User::can('role', 'delete') && count($model->roleMenus) == 0)
                                            return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']).' ';
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
                <div class="alert alert-info">
                    <strong>CATATAN:</strong> 
                    Halaman ini berisi jenis role yang terdaftar di dalam sistem. Role ini hanya perlu disetting sekali saja saat sistem berjalan pertama kali.
                </div>
            </div>
        </div>
    </div>
</div>