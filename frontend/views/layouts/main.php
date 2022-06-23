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