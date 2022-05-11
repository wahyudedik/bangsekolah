<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <!-- <header class="card-header">
                <h2 class="pull-left"><?= Html::encode($this->title) ?></h2>
            </header> -->
            <div class="card-body">
                <img src="<?= Yii::$app->request->baseUrl ?>/images/error.png" width="100px">
                <div class="alert alert-danger">
                    <?= nl2br(Html::encode($message)) ?>
                </div>
                <p>
                    The above error occurred while the Web server was processing your request.
                </p>
                <p>
                    Please contact us if you think this is a server error. Thank you.
                </p>
            </div>
        </div>
    </div>
</div>