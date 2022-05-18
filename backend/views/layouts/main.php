<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

// check session school_id
if (Yii::$app->session->get('school_id') == null) {
    // echo '<meta http-equiv="refresh" content="0; url='.Url::to(['site/login']).'">';
	Yii::$app->user->logout();
	return Yii::$app->response->redirect(['site/login']);
}

// Exclude asset from rendering
// AppAsset::register($this);
unset($this->assetBundles['yii\bootstrap\BootstrapAsset']);
unset($this->assetBundles['yii\web\JqueryAsset']);
unset($this->assetBundles['yii\web\YiiAsset']);
unset($this->assetBundles['kartik\widgets\Select2Asset']);
unset($this->assetBundles['kartik\widgets\WidgetAsset']);

$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ?></title>
    <?php $this->head() ?>
    <?= $this->render('@app/views/layouts/css') ?>
	<?= $this->render('@app/views/layouts/js') ?>
</head>
<body class="sidebar-mini" style="zoom: 0.95">
	<?php $this->beginBody(); ?>
	<div class="wrapper">
		<?= $this->render('@app/views/layouts/navbar') ?>
		<?= $this->render('@app/views/layouts/sidebar') ?>
		<div class="content-wrapper">
			<section class="content">
				<div class="container-fluid">
					
			        <?php
					foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
						echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
					}
			        ?>
			        <section class="content-header">
					    <div class="container-fluid">
					        <div class="row mb-2">
					            <div class="col-sm-6">
					                <h4 style="margin-bottom: 0px;"><?= $this->title ?></h4>
					            </div>
					            <div class="col-sm-6">
					                <?= Breadcrumbs::widget([
										'homeLink' => [ 
											'label' => 'HOME',
											'url' => Yii::$app->homeUrl,
										],
										'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
									]) ?>
					            </div>
					        </div>
					    </div>
					</section>
					<?= $content ?>
				</div>
			</section>
		</div>
	</div>
	<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>
