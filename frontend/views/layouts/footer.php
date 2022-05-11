<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Lang;
use common\helpers\Visitor;

// get choosen localization
$cookies = Yii::$app->request->cookies;
$lang = $cookies->getValue('language', 'id');

$baseUrl = Yii::$app->request->baseUrl;
?>

<footer>
    <div class="container hidden-mobile">
        <!-- <center>
            <h2 style="margin-bottom: 0px; font-size: 29px;">PT. PUS</h2>
            <h4 style="font-size: 12.5px;">Connecting New Generation</h4>
        </center> -->
        <center>
            <img src="<?= $baseUrl ?>/images/pus-motto.jpg" width="200px" alt="PT. PUS">
        </center>
        <!--
        <div class="row">
            <div class="col-md-12">
                <center>
                    <?= Html::a('ABOUT US', ['page/view', 'slug' => 'about'], ['class' => 'footer-link', 'style' => 'margin-right: 20px']) ?>
                    <?= Html::a('ADVERTISING', ['page/view', 'slug' => 'advertising'], ['class' => 'footer-link', 'style' => 'margin-right: 20px']) ?>
                    <?= Html::a('CONTACT US', ['page/view', 'slug' => 'contact'], ['class' => 'footer-link', 'style' => 'margin-right: 20px']) ?>
                    <?= Html::a('SUBSCRIBE', ['subscribe/form'], ['class' => 'footer-link', 'style' => 'margin-right: 20px']) ?>
                </center>
            </div>
        </div>
        -->
        <div class="row">
            <div class="col-md-12">
                <center>
                    <a class="footer-link" href="#" style="font-size: 12px; color: #333;">&copy; 2022 PT. PUS. ALL RIGHTS RESERVED</a>
                </center>
            </div>
        </div>
    </div>
    <div class="container hidden-desktop">
        <div class="row">
            <div class="col-md-6" style="text-align: left;">
                <img src="<?= $baseUrl ?>/images/pus-motto.png" width="200px" alt="PT. PUS">
                <br><br>
                <ul>
                    <li><?= Html::a('ABOUT US', ['page/view', 'slug' => 'about'], ['class' => 'footer-link']) ?></li>
                    <li><?= Html::a('ADVERTISING', ['page/view', 'slug' => 'advertising'], ['class' => 'footer-link']) ?></li>
                    <li><?= Html::a('CONTACT US', ['page/view', 'slug' => 'contact'], ['class' => 'footer-link']) ?></li>
                    <li><?= Html::a('SUBSCRIBE', ['subscribe/form'], ['class' => 'footer-link']) ?></li>
                </ul>
            </div>
            <div class="col-md-6" style="text-align: left;">
                <a href="https://www.facebook.com/ptpus" target="_blank">
                    <img src="<?= $baseUrl ?>/images/icons8-facebook-48.png" width="30px">
                </a>
                <a href="https://instagram.com/pt-pus.com/?igshid=jqh4uawtqowc" target="_blank">
                    <img src="<?= $baseUrl ?>/images/icons8-instagram-48.png" width="30px">
                </a>
                <a href="https://www.linkedin.com/company/ptpus" target="_blank">
                    <img src="<?= $baseUrl ?>/images/icons8-linkedin-48.png" width="30px">
                </a>
                <a href="https://twitter.com/ptpus" target="_blank">
                    <img src="<?= $baseUrl ?>/images/icons8-twitter-48.png" width="30px">
                </a>
                <br><br>
                <ul>
                    <li><a class="footer-link" href="#">&copy; 2022 PT. PUS</a></li>
                    <li><a class="footer-link" href="#">ALL RIGHTS RESERVED</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<style type="text/css">
    a.footer-link:hover {
        color: white;
    }
</style>