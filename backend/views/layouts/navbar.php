<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;
use common\helpers\Visitor;
use common\models\UserCareer;

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		</li>
		<!-- <li class="nav-item d-none d-sm-inline-block">
			<a href="<?= Url::to(['post/create']) ?>" class="nav-link">Add New Post</a>
		</li> -->
	</ul>
	<ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link" href="#">
				<strong>Browser</strong>: <?= Visitor::getBrowser() ?>, <strong>Time</strong>: <span id="clock"><?= date('d/m/Y H:i:s') ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-widget="fullscreen" href="#" role="button">
				<i class="fas fa-expand-arrows-alt"></i>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?= Url::to(['site/logout']) ?>">
				<i class="fas fa-sign-out-alt"></i>
			</a>
		</li>
	</ul>
</nav>