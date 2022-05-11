<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\OrderDetailItem;
?>

<div class="tab-pane fade" id="tab-invoice" role="tabpanel" aria-labelledby="tab-invoice">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <tr>
                        <th>Invoice Number</th>
                        <td><?= $model->invoice_number ?></td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td><?= $model->status == 'PAID' ? 'PAID' : 'UNPAID' ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <iframe src="<?= Yii::getAlias('@fronturl') ?>/api/outlet/webview/invoice/<?= $model->invoice_number ?>/download" height="400" width="100%"></iframe>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>