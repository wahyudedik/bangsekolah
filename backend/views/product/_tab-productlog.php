<?php
use common\helpers\Strings;
?>
<div class="tab-pane fade" id="tab-product" role="tabpanel" aria-labelledby="tab-product-tab">
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-sm">
                    <thead style="-webkit-text-stroke-width: thin">
                        <tr>
                            <td>#</td>
                            <td>Product Code</td>
                            <td>Product Name</td>
                            <td>Sale Price</td>
                            <td>Updated At</td>
                            <td>Updated By</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach ($productlogs as $productlog) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $productlog->code ?></td>
                            <td><?= $productlog->name ?></td>
                            <td><?= Strings::getRupiah($productlog->price_sale) ?></td>
                            <td><?= $productlog->updated_at ?></td>
                            <td><?= $productlog->updatedBy->name ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>