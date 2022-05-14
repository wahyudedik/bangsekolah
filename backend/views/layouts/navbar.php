<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;
use common\helpers\Visitor;
use common\models\UserSchool;

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;
$user_school = UserSchool::find()->where(['user_id' => $user->id])->one();

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="<?= Url::to(['transaction/create']) ?>" class="nav-link">Transaksi Pembayaran Baru</a>
		</li>
	</ul>
	<ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link" href="#">
				<?php if ($user_school) { ?>
					<strong><?= Yii::$app->session->get('school_name') ?></strong> ( <?= $user->username ?> )
				<?php } else { ?>
					<strong><?= $user->username ?></strong>
				<?php } ?>
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