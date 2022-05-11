<?php
use yii\helpers\Html;
?>

<div class="tab-pane fade" id="tab-production" role="tabpanel" aria-labelledby="tab-production-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Production</td>
                            <td>Production Date</td>
                            <td>Quantity Result</td>
                            <td>Production By</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach ($productions as $production) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= Html::a($production->production_number, ['production/view', 'id' => $production->id]) ?></td>
                            <td><?= $production->created_at ?></td>
                            <td><?= $production->quantity ?></td>
                            <td><?= $production->createdBy->name ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>