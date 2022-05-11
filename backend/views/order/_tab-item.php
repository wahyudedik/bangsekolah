<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\OrderDetailItem;
?>

<div class="tab-pane fade" id="tab-item" role="tabpanel" aria-labelledby="tab-item-tab">
    <div class="clearfix">
        <div class="row">
            <!-- <div class="col-md-6">
                <h4 class="pull-left">Packed Items</h4>
            </div> -->
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Item Code</td>
                            <td>Created At</td>
                            <td>Created By</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($model->orderDetails as $orderDetail){ ?>
                            <?php 
                            $i = 1;
                            foreach ($orderDetail->orderDetailItems as $orderDetailItem) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= Html::a($orderDetailItem->item->code, ['item/view', 'id' => $orderDetailItem->item->id]) ?></td>
                                    <td><?= $orderDetailItem->created_at ?></td>
                                    <td><?= $orderDetailItem->created_by ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>