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
Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => Yii::getAlias('@fronturl/posts/'.$post->slug)]);
Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $post->title]);
Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $post->description]);
Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => Yii::getAlias('@fronturl/uploads/posts/small-'.$post->image)]);

// Twitter
Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:url', 'content' => Yii::getAlias('@fronturl/posts/'.$post->slug)]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:title', 'content' => $post->title]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:description', 'content' => $post->description]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:image', 'content' => Yii::getAlias('@fronturl/uploads/posts/small-'.$post->image)]);

$baseUrl = Yii::$app->request->baseUrl;
$this->title = $post->title;
?>

<div class="container">
    <div class="spacer-50"></div>
    <div class="row">
        <div class="col-md-9">
            <!-- Title & Info -->
            <div style="margin-bottom: 15px;font-size: 18pt;"><?= strtoupper($post->category->name) ?></div>
            <h1 style="margin-top: 5px; overflow-wrap: break-word;"><?= $post->title ?></h1>
            <h3 style="margin-top: 0px;"><?= $post->description ?></h3>
            <!-- <div class="card-share-button">
                <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
            </div> -->
            <div style="margin-bottom: 0px;"><?= date('d.m.Y', strtotime($post->created_at)) ?></div>
            <div style="margin-bottom: 0px;">BY <?= strtoupper($post->user->name) ?></div>

            <!-- Image -->
            <?php
            if ($post->video && Visitor::getBrowser() !== 'Safari') {
                echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                    <source src="'.$baseUrl.'/uploads/posts/'.$post->video.'" type="video/mp4" />
                </video>';
            } else {
                echo '<img src="'.$baseUrl.'/uploads/posts/large-'.$post->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">';
            }
            ?>
            <div class="card-share-button">
                <span class="caption-share-story">SHARE THE STORY</span>
                <a href="https://api.whatsapp.com/send?text=<?= 'See this interesting post: '.$post->title.'. Click here '.Yii::getAlias('@fronturl') ?>/posts/<?= $post->slug ?>" target="_blank" rel="noopener" aria-label="Whatsapp">
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
                <a  href="mailto:?subject=<?= $post->title ?>&body=<?= $post->description ?>" target="_self" rel="noopener" aria-label="E-Mail">
                    <img src="<?= $baseUrl ?>/images/icons8-envelope-48.png" class="img-share-story">
                </a>
            </div>

            <!-- Content -->
            <div class="spacer-50"></div>
            <div class="spacer-30"></div>
            <div class="row">
                <div class="col-md-4 hidden-mobile">
                    <div style="margin-bottom: 0px;">RECOMMENDED</div>
                    <?php if ($recommended) : ?>
                        <?php
                        if ($recommended->video && Visitor::getBrowser() !== 'Safari') {
                            echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                                <source src="'.$baseUrl.'/uploads/posts/'.$recommended->video.'" type="video/mp4" />
                            </video>';
                        } else {
                            echo Html::a('<img src="'.$baseUrl.'/uploads/posts/large-'.$recommended->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">', ['post/view', 'slug' => $recommended->slug]);
                        }
                        ?>
                        <div style="margin-top: 10px;"><?= Html::a($recommended->title, ['post/view', 'slug' => $recommended->slug]) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <?= $post->content ?>
                    <?= $post->hashtagLabel ?>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="related-post">
    <div class="container">
        <div class="spacer-30"></div>
        <h2>RELATED POSTS</h2>
        <div class="row">
            <?php foreach ($relateds as $post):?>
            <div class="col-md-3">
                <?php
                if ($post->video && Visitor::getBrowser() !== 'Safari') {
                    echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" height="188px" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                        <source src="'.$baseUrl.'/uploads/posts/'.$post->video.'" type="video/mp4" />
                    </video>';
                } else {
                    echo Html::a('<img src="'.$baseUrl.'/uploads/posts/small-'.$post->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">', ['post/view', 'slug' => $post->slug]);
                }
                ?>
                <!-- <div class="card-share-button">
                    <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
                </div> -->
                <div class="card-subtitle"><?= strtoupper($post->user->name) ?> | <?= date('d.m.Y', strtotime($post->created_at)) ?></div>
                <h2 class="card-title">
                    <?= Html::a($post->title, ['post/view', 'slug' => $post->slug]) ?>
                </h2>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="spacer-30"></div>
    </div>
</div>