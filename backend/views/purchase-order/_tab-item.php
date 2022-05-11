<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PurchaseOrderDetailItem;
?>

<div class="tab-pane fade" id="tab-item" role="tabpanel" aria-labelledby="tab-item-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Product</td>
                            <td>Item Label</td>
                            <td>Code</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($model->purchaseOrderDetails as $purchaseOrderDetail){ ?>
                            <?php 
                            $i = 1;
                            foreach ($purchaseOrderDetail->purchaseOrderDetailItems as $poDetailItem) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $poDetailItem->product->name ?></td>
                                    <td><?= $poDetailItem->item->name ?></td>
                                    <td><?= Html::a($poDetailItem->item->code, ['item/view', 'id' => $poDetailItem->item->id]) ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>