<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;
?>

<div class="tab-pane fade" id="tab-item" role="tabpanel" aria-labelledby="tab-item-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <h4><?= $model->product ? $model->product->name : 'PRODUCT NOT SELECTED' ?></h4>
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Code</td>
                            <td>Production Price</td>
                            <td>Expired</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach ($model->items as $item) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= Html::a($item->code, ['item/view', 'id' => $item->id]) ?></td>
                            <td><?= Strings::getRupiah($item->price_purchase) ?></td>
                            <td><?= date('d M Y', strtotime($item->expired_date)) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>