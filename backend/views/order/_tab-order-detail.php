<?php
use yii\helpers\Html;
use common\helpers\Strings;
use yii\widgets\DetailView;
?>

<div class="tab-pane fade show active" id="tab-detail" role="tabpanel" aria-labelledby="tab-detail-tab">
    <div class="clearfix">
        <div class="row">
            <!-- <div class="col-md-6">
                <h4 class="pull-left">Product List</h4>
            </div> -->
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Product</td>
                            <td>Quantity</td>
                            <td>Price Sale</td>
                            <td>Subtotal</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        $total = 0;
                        foreach($model->orderDetails as $orderDetail){
                            $subtotal = $orderDetail->quantity*$orderDetail->price_sale;
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $orderDetail->product->name ?></td>
                                <td><?= $orderDetail->quantity ?></td>
                                <td><?= Strings::getRupiah($orderDetail->price_sale) ?></td>
                                <td><?= Strings::getRupiah($subtotal) ?></td>
                            </tr>
                        <?php }

                        // recount total if shipping_cost & tax exists
                        if ($model->shipping_cost > 0) {
                            $total += $model->shipping_cost;
                        }
                        if ($model->tax > 0) {
                            $total += $model->tax;
                        }
                        ?>
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
                    <tr>
                        <th>Shipping Cost</t>
                        <td><?= Strings::getRupiah($model->shipping_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Shipping Courier</t>
                        <td><?= $model->shipping_courier ?></td>
                    </tr>
                    <tr>
                        <th>Tax</t>
                        <td><?= Strings::getRupiah($model->tax) ?></td>
                    </tr>
                    <tr>
                        <th>Tax Description</t>
                        <td><?= $model->tax_description ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>