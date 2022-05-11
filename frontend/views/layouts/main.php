<?php

use yii\helpers\Html;

unset($this->assetBundles['yii\bootstrap\BootstrapAsset']);
unset($this->assetBundles['yii\web\JqueryAsset']);

$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="fb:app_id" content="xxx" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
    <?= $this->render('@app/views/layouts/css') ?>
    <?= $this->render('@app/views/layouts/js-header') ?>
    <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/272d06bdec44211b9628ce82c/cbce59300ded55ea0e7f369d4.js");</script>
</head>
<body>
    <?php $this->beginBody() ?>

    <main class="main-wrapper">
        <div class="content-wrapper">
            <div class="container">
                <?= $content ?>
            </div>
            <?= $this->render('@app/views/layouts/footer') ?>
        </div>
    </main>

    <?= $this->render('@app/views/layouts/js-footer') ?>
    <?php $this->endBody() ?>
</body >
</html>
<?php $this->endPage() ?>