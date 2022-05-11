<?php
use yii\helpers\Url;
use yii\helpers\Html;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Page Not Found';
?>
<div class="content-wrapper">
	<section class="section-wrap">
        <img src="<?= $baseUrl ?>/images/career-top-background.png" class="image-backgound-full-width hidden-mobile">
        <div class="container">
        	<div class="row">
				<div class="col-lg-6">
                    <h2 class="section-title"><span class="highlight">Page</span> Not Found!</h2>
                    <p>&nbsp;</p>
                    <h2 class="hero__subtitle">
                        We are very excited to announce the launch of our newly designed website. Visit us at https://vistaeducation.com. After several months of hard work and dedication, we are delighted to officially announce the launch on August 20, 2020. We wanted to make the new website faster, easier to navigate, and more user-friendly.
                    </h2>
                    <h2 class="hero__subtitle">
                        We're so sorry that the page you're looking for is not found. Maybe this article will help you.
                    </h2>
                    <?php foreach ($articles as $article) :
                        $url = Url::to(['article/detail', 'slug' => $article->slug]);
                        ?>
                        <a href="<?= $url ?>" class="entry-navigation__url entry-navigation--left">
                            <img src="<?= $baseUrl ?>/uploads/article/small-<?= $article->image ?>" class="entry-navigation__img" style="width: 100px; height: 100px;">
                            <div class="entry-navigation__body" style="width: 100%;">
                                <h6 class="entry-navigation__title"><?= $article->title ?></h6>
                                <p><?= $article->description ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
</div>
