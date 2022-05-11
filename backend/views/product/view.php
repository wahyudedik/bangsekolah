<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['product/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php
                    if (count($model->items) == 0) {
                        echo Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'onclick' => 'return confirm("Are you sure?");',
                        ]);
                    }
                    ?>
                </div>
            </header>
            <div class="card-body">
                <?= DetailView::widget([
                        'model' => $model,
                        'options' => [
                            'class' => 'table table-striped table-bordered detail-view'
                        ],
                        'attributes' => [
                            'productCategory.name',
                            'code',
                            'name',
                            'unit',
                            'description',
                            'stock',
                            'priceSale',
                            'lastOpnameAt',
                            ['attribute' => 'createdBy.name', 'label' => 'Created By'],
                        ],
                    ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card card-secondary card-outline card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="tab-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#tab-item" aria-controls="tab-detail" aria-selected="true">ITEMS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab-product">UPDATE LOG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab-production">PRODUCTION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tab-usage">USAGE</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tab-tabContent">
                    <?= $this->render('_tab-item', [
                        'model' => $model,
                        'items' => $items,
                    ]) ?>

                    <?= $this->render('_tab-productlog', [
                        'model' => $model,
                        'productlogs' => $productlogs,
                    ]) ?>

                    <?= $this->render('_tab-production', [
                        'model' => $model,
                        'productions' => $productions,
                    ]) ?>

                    <?= $this->render('_tab-usage', [
                        'model' => $model,
                        'usages' => $usages,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>