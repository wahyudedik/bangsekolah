<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\helpers\Strings;
use common\models\Product;
use common\models\Outlet;
use common\models\ProductOutlet;

$this->title = 'Product Availability Setting';
$this->params['breadcrumbs'][] = $this->title;

// populate data
if ($search) {
    $products = Product::find()
        ->where(['product_category_id' => $category, 'status' => 'ACTIVE'])
        ->andWhere(['like', 'name', $search])
        ->all();
} else {
    $products = Product::find()->where(['product_category_id' => $category, 'status' => 'ACTIVE'])->all();
}
$outlets = Outlet::find()->all();
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="category" id="select-category" style="margin-right: 5px;">
                        <?php
                        foreach (\common\models\ProductCategory::find()->where(['status' => 'ACTIVE'])->all() as $kat) {
                            if ($category->id == $kat->id) {
                                echo '<option selected="selected" value="'.$kat->id.'">'.$kat->name.' ('.count($kat->products).')</option>';
                            } else {
                                echo '<option value="'.$kat->id.'">'.$kat->name.' ('.count($kat->products).')</option>';
                            }
                        }
                        ?>
                    </select>

                    <input type="text" name="search" class="form-control" placeholder="Search ..." value="<?= $search ?>">
                    <span class="input-group-append">
                        <?= Html::submitButton('<i class="fas fa-search search-icon"></i>', ['class' => 'btn btn-info btn-flat']) ?>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>

            <div class="col-lg-6">
                <form id="form-product-outlet" action="" method="post">
                <div class="card-tools float-right">
                    <?php //= Html::a('<i class="fas fa-cubes fa-lg"></i> Product Category', ['product-category/index'], ['class' => 'btn btn-info']) ?>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save fa-lg"></i> Save Setting</button>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
                <input type="hidden" name="product_outlet">
                <table class="table table-bordered">
                    <tr>
                        <th rowspan="2" style="vertical-align: middle; width: 50px;">NO</th>
                        <th rowspan="2" style="vertical-align: middle; width: 100px;">CODE</th>
                        <th rowspan="2" style="vertical-align: middle; width: 300px;">PRODUCT NAME</th>
                        <th colspan="<?= count($outlets) ?>" style="text-align: center;">OUTLET</th>
                    </tr>
                    <tr>
                        <?php
                        foreach ($outlets as $outlet) {
                            echo '<th style="white-space: nowrap;">'.$outlet->name.'</th>';
                        }
                        ?>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($products as $product) :
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $product->code ?></td>
                        <td><?= $product->name ?></td>
                        <?php
                        foreach ($outlets as $outlet) {
                            $productOutlet = ProductOutlet::find()->where(['outlet_Id' => $outlet->id, 'product_id' => $product->id])->one();
                            echo '<td style="white-space: nowrap;">';
                            echo '<input type="checkbox" name="product_'.$outlet->id.'_'.$product->id.'" '.($productOutlet ? 'checked' : '').'>';
                            // echo '<input type="text" style="width: 80px;" name="product_'.$outlet->id.'_'.$product->id.'" value="'.($productOutlet ? '1' : '0').'">';
                            echo '</td>';
                        }
                        ?>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#select-category').change(function(){
        this.form.submit();
    });
</script>   