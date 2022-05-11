<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = $stockOpnameDetail->product->name;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opname', 'url' => ['stock-opname/index']];
$this->params['breadcrumbs'][] = ['label' => $stockOpnameDetail->stockOpname->so_number, 'url' => ['stock-opname/view', 'id' => $stockOpnameDetail->stockOpname->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['stock-opname/view', 'id' => $stockOpnameDetail->stockOpname->id], ['class' => 'btn btn-warning']) ?>
            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Database Stock</th>
                        <td><?= $stockOpnameDetail->stock_product ?> items</td>
                    </tr>
                    <tr>
                        <th>Real Stock</th>
                        <td><?= $stockOpnameDetail->stock_opname ?> items</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= $stockOpnameDetail->status ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <strong>SCANNED ITEMS (REAL STOCK)</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Item Code</td>
                            <td>Item Label</td>
                            <td>Status</td>
                            <td>Scanned At</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($stockOpnameDetailItems as $stockOpnameDetailItem){ ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $stockOpnameDetailItem->code ?></td>
                                <td><?= $stockOpnameDetailItem->item->name ?></td>
                                <td><?= $stockOpnameDetailItem->status ?></td>
                                <td><?= $stockOpnameDetailItem->scanned_at ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <strong>DELETED ITEMS</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Item Code</td>
                            <td>Item Label</td>
                            <td>Reason</td>
                            <td>Deleted At</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($stockOpnameDeletedItems as $stockOpnameDeletedItem){ ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $stockOpnameDeletedItem->code ?></td>
                                <td><?= $stockOpnameDeletedItem->item->name ?></td>
                                <td><?= $stockOpnameDeletedItem->reason ?></td>
                                <td><?= $stockOpnameDeletedItem->deleted_at ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
