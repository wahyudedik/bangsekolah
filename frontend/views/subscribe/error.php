<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Subscribe Error';
?>

<div class="container">
    <div class="spacer-50"></div>
    <div class="row">
        <div class="col-md-9">
            <h1 style="margin-top: 5px; overflow-wrap: break-word;">Subscribe Error</h1>
            <h3 style="margin-top: 0px;">Sorry, something happened</h3>
            <!-- <img src="<?= $baseUrl ?>/images/newsletter.png" width="80px"> -->
            <div class="spacer-30"></div>
            <div class="row">
                <div class="col-md-12">
                    <p>The server was unable to complete your request. Why did this happen?</p>
                    <ul>
                        <li>Our servers are just too busy for our own good</li>
                        <li>Your email already been registered to our mailing list</li>
                        <li>The internet broke</li>
                        <li>We had a littel hiccup. Try reloading the page</li>
                    </ul>
                    <p>We apoligize for the inconvenience and appreciate your patience. Thank you for reading The S Media.</p>
                    <br><br>
                    PT. PUS
                </div>
            </div>
            <div class="spacer-50"></div>
        </div>
    </div>
</div>