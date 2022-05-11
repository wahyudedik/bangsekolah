<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\helpers\Strings;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="category" id="select-category" style="margin-right: 5px;">
                        <option <?= $category == 'ALL' ? 'selected="selected"' : '' ?> value="ALL">All Category</option>
                        <?php
                        foreach (\common\models\ProductCategory::find()->where(['status' => 'ACTIVE'])->all() as $kat) {
                            if ($category && $category->id == $kat->id) {
                                echo '<option selected="selected" value="'.$kat->id.'">'.$kat->name.' ('.count($kat->products).')</option>';
                            } else {
                                echo '<option value="'.$kat->id.'">'.$kat->name.' ('.count($kat->products).')</option>';
                            }
                        }
                        ?>
                    </select>

                    <input type="text" name="q" class="form-control" placeholder="Search product ..." value="<?=   $q ?>">
                    <span class="input-group-append">
                        <?= Html::submitButton('<i class="fas fa-search search-icon"></i>', ['class' => 'btn btn-info btn-flat']) ?>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?php //= Html::a('<i class="fas fa-cubes fa-lg"></i> Product Category', ['product-category/index'], ['class' => 'btn btn-info']) ?>
                    <?= Html::a('<i class="fas fa-plus fa-lg"></i> Create Product', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                    <?= Html::a('<i class="fas fa-download fa-lg"></i> Download', ['export', 'category' => $category, 'q' => $q], ['class' => 'btn btn-primary']) ?>
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

                    //['attribute' => 'smallImage', 'format' => 'raw'],
                    'code',
                    'name',
                    ['attribute' => 'productCategory.name'],
                    'unit',
                    'description',
                    'stock',
                    //'price_purchase',
                    //'price_sale',
                    ['label' => 'Price Sale', 'value' => function($model){
                        return Strings::getRupiah($model->price_sale);
                    }],
                    //'status',
                    //'created_at',
                    //'updated_at',
                    //'created_by',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Action',
                            'template' => '{view}{update}{delete}', 
                            'contentOptions' => ['style' => 'width:150px;'],
                            'buttons' => [
                                'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                                'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                                'delete' => function ($url, $model) { return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']).' '; },
                            ]
                        ],
                    ],
                ]); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#select-category').change(function(){
        this.form.submit();
    });
</script>   