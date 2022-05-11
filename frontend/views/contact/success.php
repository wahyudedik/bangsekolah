<?php
use common\helpers\Lang;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = Lang::text('contact-success-title');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="red-heading">
                    <?= Lang::text('contact-success-title') ?> <?= $model->name ?>
                </h1>
                <img src="<?= $baseUrl ?>/images/success.png" width="250px">
                <p class="sub-heading text-bold" style="text-align: center;"><?= Lang::text('contact-success-description') ?></p>
                <p><?= Lang::text('contact-success-content') ?></p>
            </div>
        </div>
    </div>
</div>