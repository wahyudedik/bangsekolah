<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Subscribe Success';
?>

<div class="container">
    <div class="spacer-50"></div>
    <div class="row">
        <div class="col-md-9">
            <h1 style="margin-top: 5px; overflow-wrap: break-word;">Subscribe Success</h1>
            <h3 style="margin-top: 0px;">Thanks for joining our big family.</h3>
            <!-- <img src="<?= $baseUrl ?>/images/newsletter.png" width="80px"> -->
            <div class="spacer-30"></div>
            <div class="row">
                <div class="col-md-12">
                    Your email <span style="color: red;"><?= $email ?></span> is successfully registered to our mailing list now. We will look over your message and get back to you by tomorrow. In the meantime, you can check the FAQ section, look over our new product collection or browse through our latest blog posts.
                    <br><br>
                    PT. PUS
                </div>
            </div>
            <div class="spacer-50"></div>
        </div>
    </div>
</div>