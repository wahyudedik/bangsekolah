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
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MQDK1EZ1S5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-MQDK1EZ1S5');
    </script>

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
    <?= $this->render('@app/views/layouts/css') ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?= $this->render('@app/views/layouts/footer') ?>
    <?= $this->render('@app/views/layouts/js-footer') ?>
    <?php $this->endBody() ?>
</body >
</html>
<?php $this->endPage() ?>