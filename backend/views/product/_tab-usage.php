<?php
use yii\helpers\Html;
use common\models\ProductionItem;
?>

<div class="tab-pane fade" id="tab-usage" role="tabpanel" aria-labelledby="tab-production-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Production</td>
                            <td>Date</td>
                            <td>Item</td>
                            <td>Production By</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1; foreach ($usages as $detail) {
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= Html::a($detail->production->production_number, ['production/view', 'id' => $detail->production->id]) ?></td>
                            <td><?= $detail->production->created_at ?></td>
                            <td>
                                <?php
                                $items = ProductionItem::find()->where(['production_id' => $detail->production->id])->all();
                                foreach ($items as $item) {
                                    echo $item->item->code.' ('.$item->amount.' '.$item->unit.')';
                                }
                                ?>
                            <td><?= $detail->createdBy->name ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>