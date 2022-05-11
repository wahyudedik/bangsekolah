<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <header class="card-header">
                <h2 class="pull-left"><?= Html::encode($this->title) ?></h2>
                <div class="filter-block pull-right">
                    <div class="form-group pull-left" style="margin-bottom: 0px;">
                        <?php  $form = ActiveForm::begin([
                            'method' => 'get',
                            'action' => Url::to(['index']),
                            'options' => [
                                'class' => 'form-inline'
                            ]
                        ]);
                        ?>
                            <input type="text" name="q" class="form-control" placeholder="Search ..." value="<?=  $q ?>">
                            <i class="fas fa-search search-icon"></i>
                        <?php  ActiveForm::end() ?>
                    </div>
                    <?=  Html::a('<i class="fas fa-plus-circle fa-lg"></i> Create Category', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary pull-right']) ?> 
                </div>
            </header>
            
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
                                'majorCount',
                                'created_at',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Action',
                                    'template' => '{view}{update}{delete}', 
                                    'contentOptions' => ['style' => 'width:150px;'],
                                    'buttons' => [
                                        'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                                        'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                                        'delete' => function ($url, $model) {
                                            if (count($model->majorCategories) == 0 ) {
                                                return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']).' ';
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