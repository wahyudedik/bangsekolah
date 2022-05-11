<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;
use common\models\PurchaseOrderDetail;

// set whether production product or purchase product
$priceLabel = 'Production Cost';
$dateLabel = 'Production Date';
$purchaseOrderDetail = PurchaseOrderDetail::find()->where(['product_id' => $model->id])->one();
if ($purchaseOrderDetail) {
    $priceLabel = 'Purchase Price';
    $dateLabel = 'Purchase Date';
}
?>

<div class="tab-pane fade show active" id="tab-item" role="tabpanel" aria-labelledby="tab-item-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Code</td>
                            <td><?= $priceLabel ?></td>
                            <td><?= $dateLabel ?></td>
                            <td>Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach ($items as $item) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= Html::a($item->code, ['item/view', 'id' => $item->id]) ?></td>
                            <td><?= Strings::getRupiah($item->price_purchase) ?></td>
                            <td><?= date('d M Y', strtotime($item->created_at)) ?></td>
                            <td><?= $item->amount.' '.$item->unit ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>