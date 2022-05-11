<?php 
use yii\helpers\Html;
use common\models\ProductionItem;

$items = ProductionItem::find()->where(['item_id' => $model->id])->orderBy(['id' => SORT_DESC])->all();
?>
<div class="tab-pane fade show active" id="tab-usage" role="tabpanel" aria-labelledby="tab-usage-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <tr>
                        <th>PROD NUMBER</th>
                        <th>DATE</th>
                        <th>USAGE</th>
                        <th>CREATED BY</th>
                    </tr>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?= Html::a($item->production->production_number, ['production/view', 'id' => $item->production->id]) ?><br>
                                <?= $item->production->recipe->product->name ?>
                            </td>
                            <td><?= date('d M Y H:i:s', strtotime($item->production->created_at)) ?></td>
                            <td><?= $item->amount.' '.$item->unit ?></td>
                            <td><?= $item->production->createdBy->name ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>