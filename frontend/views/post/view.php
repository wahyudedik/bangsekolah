<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;
use common\helpers\Visitor;

// Primary Meta Tags
Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => $post->title]);
Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $post->description]);
Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $post->tagKeywords]);

// Open Graph / Facebook
Yii::$app->view->registerMetaTag(['property' => 'og:type', 'content' => 'website']);
Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => Yii::getAlias('@fronturl/posts/' . $post->slug)]);
Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $post->title]);
Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $post->description]);
Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => Yii::getAlias('@fronturl/uploads/posts/small-' . $post->image)]);

// Twitter
Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:url', 'content' => Yii::getAlias('@fronturl/posts/' . $post->slug)]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:title', 'content' => $post->title]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:description', 'content' => $post->description]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:image', 'content' => Yii::getAlias('@fronturl/uploads/posts/small-' . $post->image)]);

$baseUrl = Yii::$app->request->baseUrl;
$this->title = $post->title;
?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Title & Info -->
                <div class="post-category"><?= strtoupper($post->postCategory->name) ?></div>
                <h1 class="post-title"><?= $post->title ?></h1>
                <div class="post-meta">
                    <div class="post-date"><?= date('d M Y', strtotime($post->created_at)) ?></div>
                    <div class="post-author">BY <?= strtoupper($post->createdBy->name) ?></div>
                </div>

                <!-- Image -->
                <div class="post-image">
                    <?= '<img src="' . $baseUrl . '/uploads/posts/large-' . $post->image . '" class="img-fluid" onerror="this.onerror=null; this.src=\'' . Yii::getAlias('@fronturl') . '/images/default-post.webp\';">';
                    ?>
                </div>

                <!-- Description -->
                <div class="post-description"><?= $post->description ?></div>

                <!-- Share Buttons -->
                <div class="post-share">
                    <span class="caption-share-story">SHARE THE STORY</span>
                    <a href="https://api.whatsapp.com/send?text=<?= 'See this interesting post: ' . $post->title . '. Click here ' . Yii::getAlias('@fronturl') ?>/posts/<?= $post->slug ?>" target="_blank" rel="noopener" aria-label="Whatsapp">
                        <img src="<?= $baseUrl ?>/images/icons8-whatsapp-48.png" class="img-share-story">
                    </a>
                    <a href="https://facebook.com/sharer/sharer.php?u=<?= Yii::getAlias('@fronturl') ?>/posts/<?= $post->slug ?>" target="_blank" rel="noopener" aria-label="Facebook">
                        <img src="<?= $baseUrl ?>/images/icons8-facebook-48.png" class="img-share-story">
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= Yii::getAlias('@fronturl') ?>/posts/<?= $post->slug ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <img src="<?= $baseUrl ?>/images/icons8-linkedin-48.png" class="img-share-story">
                    </a>
                    <a href="https://twitter.com/intent/tweet/?text=<?= $post->title ?>&url=<?= Yii::getAlias('@fronturl') ?>/posts/<?= $post->slug ?>" target="_blank" rel="noopener" aria-label="Twitter">
                        <img src="<?= $baseUrl ?>/images/icons8-twitter-48.png" class="img-share-story">
                    </a>
                    <a href="mailto:?subject=<?= $post->title ?>&body=<?= $post->description ?>" target="_self" rel="noopener" aria-label="E-Mail">
                        <img src="<?= $baseUrl ?>/images/icons8-envelope-48.png" class="img-share-story">
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="related-post">
                    <h3 class="related-post-title mt-4">RELATED POSTS</h3>
                    <hr style="border-top: 3px solid #0000ff; margin: 20px 0;">
                    <div class="row">
                        <?php foreach ($relateds as $relate) : ?>
                            <div class="col-md-12 mb-4">
                                <div class="row no-gutters">
                                    <div class="col-md-6">
                                        <!-- Image -->
                                        <?= Html::a('<img src="' . $baseUrl . '/uploads/posts/small-' . $relate->image . '" class="img-fluid card-img" onerror="this.onerror=null; this.src=\'' . Yii::getAlias('@fronturl') . '/images/default-post.webp\';">', ['post/view', 'slug' => $relate->slug]); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <!-- Title & Info -->
                                            <h4 class="card-title"><?= Html::a($relate->title, ['post/view', 'slug' => $relate->slug]) ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>