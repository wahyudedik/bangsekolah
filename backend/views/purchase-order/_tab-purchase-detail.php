<?php
use yii\helpers\Html;
use common\helpers\Strings;
use yii\widgets\DetailView;
?>

<div class="tab-pane fade show active" id="tab-detail" role="tabpanel" aria-labelledby="tab-detail-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Product</td>
                            <td>Amount</td>
                            <td>Quantity</td>
                            <td>Price</td>
                            <td align="right">Subtotal</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        $total = 0;
                        foreach($model->purchaseOrderDetails as $purchaseOrderDetail){
                            $subtotal = $purchaseOrderDetail->quantity*$purchaseOrderDetail->price_purchase;
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $purchaseOrderDetail->product->name ?></td>
                                <td><?= $purchaseOrderDetail->amount.' '.$purchaseOrderDetail->unit ?></td>
                                <td><?= $purchaseOrderDetail->quantity ?></td>
                                <td><?= Strings::getRupiah($purchaseOrderDetail->price_purchase) ?></td>
                                <td align="right"><?= Strings::getRupiah($purchaseOrderDetail->quantity*$purchaseOrderDetail->price_purchase) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <table class="table">
                    <tr style="background: #ddd;">
                        <th>Total</t>
                        <td align="right">
                            <span style="font-size: 20px; font-weight: 800;">
                                <?= Strings::getRupiah($total) ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>