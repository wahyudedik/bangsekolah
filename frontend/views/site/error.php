<?php
use yii\helpers\Html;

$this->title = 'Page Not Found';
$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <div class="spacer-50"></div>
                <div class="spacer-50"></div>
                <h2 class="red-heading">
                    SORRY, THE PAGE YOU ARE LOOKING FOR IS NOT FOUND
                </h2>
                <p class="sub-heading text-bold" style="text-align: center;">
                	<?= Html::encode($exception->getMessage()) ?>
                </p>
                <div class="spacer-50"></div>
                <div class="spacer-50"></div>
            </div>
        </div>
    </div>
</div>