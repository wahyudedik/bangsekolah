<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;

// Primary Meta Tags
Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => $page->title]);
Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $page->description]);
Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $page->tagKeywords]);

// Open Graph / Facebook
Yii::$app->view->registerMetaTag(['property' => 'og:type', 'content' => 'website']);
Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => Yii::getAlias('@fronturl/posts/'.$page->slug)]);
Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $page->title]);
Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $page->description]);
Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => Yii::getAlias('@fronturl/uploads/posts/large-'.$page->image)]);

// Twitter
Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:url', 'content' => Yii::getAlias('@fronturl/posts/'.$page->slug)]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:title', 'content' => $page->title]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:description', 'content' => $page->description]);
Yii::$app->view->registerMetaTag(['property' => 'twitter:image', 'content' => Yii::getAlias('@fronturl/uploads/posts/large-'.$page->image)]);

$baseUrl = Yii::$app->request->baseUrl;
$this->title = $page->title;
?>

<div class="container">
    <div class="spacer-50"></div>
    <div class="row">
        <div class="col-md-9">
            <!-- Title & Info -->
            <h1 style="margin-top: 5px; overflow-wrap: break-word;"><?= $page->title ?></h1>
            <h3 style="margin-top: 0px;"><?= $page->description ?></h3>
            <!-- <div class="card-share-button">
                <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
            </div> -->
            <div style="margin-bottom: 0px;"><?= date('d.m.Y', strtotime($page->created_at)) ?></div>
            <div style="margin-bottom: 0px;">BY <?= strtoupper($page->user->name) ?></div>

            <!-- Image -->
            <?php
            if ($page->video) {
                echo '<video autoplay="autoplay" loop="loop" muted="muted" width="100%" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                    <source src="'.$baseUrl.'/uploads/pages/'.$page->video.'" type="video/mp4" />
                </video>';
            } else {
                echo '<img src="'.$baseUrl.'/uploads/pages/large-'.$page->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">';
            }
            ?>

            <!-- Content -->
            <div class="spacer-50"></div>
            <div class="row">
                <div class="col-md-12">
                    <?= $page->content ?>
                </div>
            </div>
        </div>
    </div>
</div>