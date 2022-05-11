<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\StockOpnameDetailItem;
?>

<div class="tab-pane fade" id="tab-item" role="tabpanel" aria-labelledby="tab-item-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-md-6">
                <h4 class="pull-left">List Item Opname</h4>
            </div>
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Product</td>
                            <td>Item Code</td>
                            <td>Status</td>
                            <td>Scanned at</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach($detail_opnames as $detail_opname){ ?>
                            <?php 
                            $items = StockOpnameDetailItem::find()->where(['stock_opname_detail_id' => $detail_opname->id])->all();
                            
                            foreach ($items as $itemproduct) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $itemproduct->product->name ?></td>
                                    <td><?= $itemproduct->code ?></td>
                                    <td><?= $itemproduct->status ?></td>
                                    <td><?= $itemproduct->scanned_at ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>