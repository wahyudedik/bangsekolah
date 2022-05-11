<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Recipe */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Recipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['recipe/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php if (count($model->recipeDetails) == 0) { ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'onclick' => 'return confirm("Are you sure?");',
                        ]) ?>
                    <?php } ?>
                </div>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table table-striped table-bordered detail-view'
                    ],
                    'attributes' => [
                        // 'id',
                        //'product_id',
                        ['attribute' => 'product.name'],
                        'name',
                        'expected_quantity',
                        //'image',
                        // [
                        //     'label' => 'Image',
                        //     'format' => 'raw',
                        //     'value' => function($model) {
                        //         if ($model->image) {
                        //             return Html::img(Yii::getAlias('@backurl/uploads/recipes/small-'.$model->image), ['width' => '150px', 'class' => 'img img-rounded']);
                        //         } else {
                        //             return Html::img('https://via.placeholder.com/50?text=F', ['class' => 'img img-rounded']);
                        //         }
                        //     }
                        // ],
                        'description:ntext',
                        // 'status',
                        'created_at',
                        ['attribute' => 'createdBy.name', 'label' => 'Created By'],
                    ],
                ]) ?>
                
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <h6 class="float-left"><b>Raw Materials</b></h6>
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus"></i> Add Raw Material', ['recipe-detail/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                    <?php if(count($model->recipeDetails) > 0) { ?>
                        <?= Html::a('<i class="fas fa-pen"></i> Update Raw Materials', ['recipe-detail/index', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php } ?>
                </div>
                
            </header>
            <div class="card-body">
                <?php
                if (count($model->recipeDetails) == 0) {
                    echo '<div class="alert alert-info">No raw material added</div>';
                } else {
                ?>
                    <table class="table table-striped table-bordered detail-view">
                        <tr>
                            <th>Product Name</th>
                            <th>Amount</th>
                            <th>Note</th>
                        </tr>
                        <?php foreach ($model->recipeDetails as $recipeDetail) { ?>
                            <tr>
                                <th width="40%"><?= $recipeDetail->product->name ?></th>
                                <td width="10%"><?= $recipeDetail->amount.' '.$recipeDetail->unit ?></td>
                                <td><?= $recipeDetail->note ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
