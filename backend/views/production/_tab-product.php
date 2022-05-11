<?php
use yii\helpers\Html;
use common\helpers\Strings;
use yii\widgets\DetailView;
use common\models\ProductionItem;
?>

<div class="tab-pane fade show active" id="tab-product" role="tabpanel" aria-labelledby="tab-product-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Product</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($model->recipe) :
                            $i=1;
                            foreach ($model->recipe->recipeDetails as $recipeDetail): ?>
                                <tr style="background: #eee;">
                                    <td><?= $i++ ?></td>
                                    <td colspan="3"><?= $recipeDetail->product->name.' ('.($model->serving*$recipeDetail->amount).' '.$recipeDetail->unit.')' ?></td>
                                </tr>
                                    <?php
                                    $productionItems = ProductionItem::find()->where(['production_id' => $model->id, 'product_id' => $recipeDetail->product_id])->all();
                                    foreach ($productionItems as $productionItem):
                                        $basePrice = ($productionItem->amount/$productionItem->item->base_amount) * $productionItem->item->price_purchase;
                                        ?>

                                        <tr>
                                            <td></td>
                                            <td><?= Html::a($productionItem->item->code.'<br>'.$productionItem->item->name.' ('.$productionItem->amount.' '.$productionItem->unit.')', ['item/view', 'id' => $productionItem->item->id]) ?></td>
                                            <td>Exp: <?= date('d M Y', strtotime($productionItem->item->expired_date)) ?></td>
                                            <td><?= Strings::getRupiah($basePrice) ?></td>
                                        </tr>

                                    <?php endforeach; ?>
                            <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
                <table class="table">
                    <tr style="background: #ddd;">
                        <td><strong>Production Cost</strong></td>
                        <td align="right">
                            <span style="font-size: 20px; font-weight: 800;"><?= $model->productionCostRupiah ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>