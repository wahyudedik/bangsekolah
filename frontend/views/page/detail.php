<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Lang;
use common\helpers\Strings;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = $model->title;
?>

<div class="sub_header header-background">
    <div id="intro_txt" class="animated fadeInDown">
        <h1><strong><?= $model->title ?></strong></h1>
        <h4><?= $model->description ?></h4>
    </div>
</div>
<div class="container_gray_bg">
    <div class="container">
        <div class="spacer-50"></div>
        <div class="article-detail-box">
            <div class="article-box-body">
                <h2 class="article-heading"><?= $model->title ?></h2>
                <!-- <div class="post-info">
                    <i class="icon-calendar"></i> <?= Strings::getFullDate($model->created_at) ?> &nbsp;&nbsp;&nbsp; <i class="icon-eye"></i> <?= $model->read ?> Kali
                </div> -->
                <?php
                // get choosen localization
                $cookies = Yii::$app->request->cookies;
                $lang = $cookies->getValue('language', 'id');
                if ($lang == 'id') {
                    echo $model->id_content;
                } else if ($lang == 'en') {
                    echo $model->en_content;
                }
                ?>
            </div>
        </div>
        <div class="spacer-50"></div>
    </div>
</div>

<style type="text/css">
    .header-background {
        /*background: linear-gradient(0deg, rgba(38, 103, 169, 0.6), rgba(38, 103, 169, 0.6)), url(<?= $baseUrl ?>/uploads/pages/large-<?= $model->image ?>);*/
        background: url(<?= $baseUrl ?>/uploads/pages/large-<?= $model->image ?>);
        background-size: cover;
    }
</style>