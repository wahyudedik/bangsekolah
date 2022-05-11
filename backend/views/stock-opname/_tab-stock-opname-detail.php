<?php
use yii\helpers\Html;
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
                            <td>Database Stock</td>
                            <td>Real Stock</td>
                            <td>Status</td>
                            <td>Detail</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($model->stockOpnameDetails as $stockOpnameDetail){ ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $stockOpnameDetail->product->name ?></td>
                                <td><?= $stockOpnameDetail->stock_product ?></td>
                                <td><?= $stockOpnameDetail->stock_opname ?></td>
                                <td><?= $stockOpnameDetail->status ?></td>
                                <td><?= Html::a('<i class="fa fa-eye"></i>', ['stock-opname-detail-item/index', 'id' => $stockOpnameDetail->id], ['class' => 'btn btn-success']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>