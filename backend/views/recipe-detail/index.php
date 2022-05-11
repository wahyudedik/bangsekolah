<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingredients';
$this->params['breadcrumbs'][] = ['label' => 'Recipes', 'url' => ['recipe/index']];
$this->params['breadcrumbs'][] = ['label' => $recipe->name, 'url' => ['recipe/view', 'id' => $recipe->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['recipe/view', 'id' => $recipe->id], ['class' => 'btn btn-warning']) ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index', 'id' => $id], ['class' => 'btn btn-primary']) ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary'=> '',
                'tableOptions' => [
                    'class' => 'table table-striped table-hover'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'recipe_id',
                    //'product_id',
                    // ['attribute' => 'recipe.name', 'label' => 'Recipe'],
                    ['attribute' => 'product.name', 'label' => 'Product'],
                    [
                        'label' => 'Amount',
                        'value' => function($model) {
                            return $model->amount.' '.$model->unit;
                        }
                    ],
                    // 'amount',
                    // 'unit',
                    'note:ntext',
                    ['attribute' => 'createdBy.name', 'label' => 'Created By'],
                    //'created_at',
                    //'created_by',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{update}{delete}', 
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                            'delete' => function ($url, $model) { return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']).' '; },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
