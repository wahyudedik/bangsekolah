<?php
use common\helpers\Strings;
use yii\helpers\Html;
?>
<div class="tab-pane fade show active" id="tab-info" role="tabpanel" aria-labelledby="tab-info-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <?php if ($model->production_id): ?>
                        <tr>
                            <th>Production Batch</th>
                            <td><?= Html::a($model->production->production_number, ['production/view', 'id' => $model->production_id]) ?></td>
                        </tr>
                        <tr>
                            <th>Production Price (per item)</th>
                            <td><?= Strings::getRupiah($model->price_purchase) ?></td>
                        </tr>
                        <tr>
                            <th>Expired Date</th>
                            <td><?= date('d M Y', strtotime($model->expired_date)) ?></td>
                        </tr>

                        <tr>
                            <th>Production Date Time</th>
                            <td><?= date('d M Y H:i:s', strtotime($model->production->created_at )) ?></td>
                        </tr>
                        <tr>
                            <th>Produced By</th>
                            <td><?= $model->production->createdBy->name ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th>Purchase Order</th>
                            <td><?= Html::a($model->purchaseOrder->po_number, ['purchase-order/view', 'id' => $model->purchase_order_id]) ?></td>
                        </tr>
                        <tr>
                            <th>Supplier Name</th>
                            <td><?= $model->purchaseOrder->supplier->name ?></td>
                        </tr>
                        <tr>
                            <th>Purchase Price</th>
                            <td><?= Strings::getRupiah($model->price_purchase) ?></td>
                        </tr>
                        <tr>
                            <th>Expired Date</th>
                            <td><?= date('d M Y', strtotime($model->expired_date)) ?></td>
                        </tr>
                        <tr>
                            <th>Purchased At</th>
                            <td><?= date('d M Y H:i:s', strtotime($model->purchaseOrder->created_at )) ?></td>
                        </tr>
                        <tr>
                            <th>Purchased By</th>
                            <td><?= $model->purchaseOrder->createdBy->name ?></td>
                        </tr>
                    <?php endif;?>
                </table>
            </div>
        </div>
    </div>
</div>