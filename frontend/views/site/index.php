<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Post;
use common\helpers\Image;
use common\helpers\Visitor;

// Primary Meta Tags
Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => 'PT. PUS']);
Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'PT. PUS: Produsen makanan berkualitas']);
Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => 'Indonesia,food,factory']);

// Open Graph / Facebook
Yii::$app->view->registerMetaTag(['property' => 'og:type', 'content' => 'website']);
Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => 'https://pt-pus.com']);
Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => 'PT. PUS']);
Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => 'PT. PUS: Produsen makanan berkualitas']);
Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => Yii::getAlias('@fronturl').'/images/ptpus.jpg']);

// Twitter
Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:url', 'content' => 'https://pt-pus.com']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:title', 'content' => 'PT. PUS']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:description', 'content' => 'PT. PUS: Produsen makanan berkualitas']);
Yii::$app->view->registerMetaTag(['property' => 'twitter:image', 'content' => Yii::getAlias('@fronturl').'/images/ptpus.jpg']);

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'PT. PUS';

/**
 * Get last post image thumbnails
 */
// $backgroundUrl = '';
$backgroundUrl = $baseUrl.'/images/default-post.webp';
if (!empty($new->image)) {
    $explodedName = explode('.', $new->image);
    $extension = end($explodedName);
    if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'webp' || $extension == 'jpeg') {
        $backgroundUrl = Yii::getAlias('@fronturl').'/uploads/posts/large-'.$new->image;
    } else {
        $backgroundUrl = Yii::getAlias('@fronturl').'/uploads/posts/video-'.$new->image;
    }    
}
// if (!Image::checkRemoteFile($backgroundUrl)) {
    // $backgroundUrl = $baseUrl.'/images/default-post.webp';
// }
?>
<?php if (!empty($new->video) && Visitor::getBrowser() !== 'Safari'): ?>
    <div class="sub_header home-image">
        <video type="video/mp4" class="video-header" autoplay="autoplay" loop="loop" muted="muted" width="100%" height="400px" poster="<?= Yii::getAlias('@fronturl/images/default-video.webp') ?>">
            <source src="<?= $baseUrl ?>/uploads/posts/<?= $new->video ?>" type="video/mp4" />
        </video>
        <div class="video-intro animated fadeInDown">
            <h1>
                <strong>
                    <?= Html::a($new->title, ['post/view', 'slug' => $new->slug], ['style' => 'color: white;']) ?>
                </strong>
            </h1>
            <h4><?= $new->description ?></h4>
        </div>
    </div>
<?php else: ?>
    <div class="sub_header home-image" style="background-image: url(<?= $backgroundUrl ?>); background-size: cover;">
        <div id="intro_txt" class="animated fadeInDown">
            <h1>
                <strong>
                    <?= Html::a($new->title, ['post/view', 'slug' => $new->slug], ['style' => 'color: white;']) ?>
                </strong>
            </h1>
            <h4><?= $new->description ?></h4>
        </div>
    </div>
<?php endif; ?>

<div class="rainbow">
    <div class="container">
        <!-- NEW UPDATE -->
        <div class="spacer-50"></div>
        <h1>NEW UPDATE</h1>
        <?php if (count($updatesTwo)>0) : ?>
            <div class="row">
                <?php foreach ($updatesTwo as $post):?>
                    <div class="col-md-6">
                        <?php
                        if ($post->video && Visitor::getBrowser() !== 'Safari') {
                            echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" height="400px" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                                <source src="'.$baseUrl.'/uploads/posts/'.$post->video.'" type="video/mp4" />
                            </video>';
                        } else {
                            echo Html::a('<img src="'.$baseUrl.'/uploads/posts/small-'.$post->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">', ['post/view', 'slug' => $post->slug]);
                        }
                        ?>
                        <!--<div class="card-share-button">
                            <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
                        </div>-->
                        <div class="card-subtitle"><?= strtoupper($post->user->name) ?> | <?= date('d.m.Y', strtotime($post->created_at)) ?></div>
                        <h2 class="card-title">
                            <?= Html::a($post->title, ['post/view', 'slug' => $post->slug]) ?>
                        </h2>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (count($updatesThree)>0) : ?>
            <div class="spacer-10 hidden-mobile"></div>   
            <div class="row">
                <?php foreach ($updatesThree as $post):?>
                    <div class="col-md-4">
                        <?php
                        if ($post->video && Visitor::getBrowser() !== 'Safari') {
                            echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" height="258px" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                                <source src="'.$baseUrl.'/uploads/posts/'.$post->video.'" type="video/mp4" />
                            </video>';
                        } else {
                            echo Html::a('<img src="'.$baseUrl.'/uploads/posts/small-'.$post->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">', ['post/view', 'slug' => $post->slug]);
                        }
                        ?>
                        <!--<div class="card-share-button">
                            <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
                        </div>-->
                        <div class="card-subtitle"><?= strtoupper($post->user->name) ?> | <?= date('d.m.Y', strtotime($post->created_at)) ?></div>
                        <h2 class="card-title">
                            <?= Html::a($post->title, ['post/view', 'slug' => $post->slug]) ?>
                        </h2>
                    </div>
                <?php endforeach; ?>    
            </div>
        <?php endif; ?>

        <!-- CATEGORIES -->
        <?php
        foreach ($categories as $category):
            $arrIdCategories = [$category->id];
            foreach ($category->childs as $child) {
                $arrIdCategories[] = $child->id;
            }
            $postsTwo = Post::find()->where(['status' => 1])->andWhere(['in', 'post_category_id', $arrIdCategories])->andWhere(['not in', 'id', $lastPostsId])->orderBy(['published_at' => SORT_DESC])->limit(2)->all();
            $postsThree = Post::find()->where(['status' => 1])->andWhere(['in', 'post_category_id', $arrIdCategories])->andWhere(['not in', 'id', $lastPostsId])->orderBy(['published_at' => SORT_DESC])->offset(2)->limit(3)->all();
            ?>

            <?php if (count($postsTwo)>0 && count($postsThree)>0): ?>
                <div class="spacer-30"></div>
                <h1><?= $category->name ?></h1>
                <?php if (count($postsTwo)>0) : ?>
                    <div class="row">
                        <?php foreach ($postsTwo as $post):?>
                            <div class="col-md-6">
                                <?php
                                if ($post->video && Visitor::getBrowser() !== 'Safari') {
                                echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" height="400px" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                                        <source src="'.$baseUrl.'/uploads/posts/'.$post->video.'" type="video/mp4" />
                                    </video>';
                                } else {
                                    echo Html::a('<img src="'.$baseUrl.'/uploads/posts/small-'.$post->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">', ['post/view', 'slug' => $post->slug]);
                                }
                                ?>
                                <!--<div class="card-share-button">
                                    <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
                                </div>-->
                                <div class="card-subtitle"><?= strtoupper($post->user->name) ?> | <?= date('d.m.Y', strtotime($post->created_at)) ?></div>
                                <h2 class="card-title">
                                    <?= Html::a($post->title, ['post/view', 'slug' => $post->slug]) ?>
                                </h2>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (count($postsThree)>0) : ?>
                    <div class="spacer-10 hidden-mobile"></div>   
                    <div class="row">
                        <?php foreach ($postsThree as $post): ?>
                            <div class="col-md-4">
                                <?php
                                if ($post->video && Visitor::getBrowser() !== 'Safari') {
                                    echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" height="258px" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                                        <source src="'.$baseUrl.'/uploads/posts/'.$post->video.'" type="video/mp4" />
                                    </video>';
                                } else {
                                    echo Html::a('<img src="'.$baseUrl.'/uploads/posts/small-'.$post->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">', ['post/view', 'slug' => $post->slug]);
                                }
                                ?>
                                <!--<div class="card-share-button">
                                    <svg viewBox="0 0 18 18" width="19" height="19" class="" fill="none" stroke="#000"><g fill-rule="evenodd"><path d="M.5.5h17v17H.5z"></path><g stroke-linecap="square"><path d="M9.19 13.762V4.238M4.238 9h9.524"></path></g></g></svg>
                                </div>-->
                                <div class="card-subtitle"><?= strtoupper($post->user->name) ?> | <?= date('d.m.Y', strtotime($post->created_at)) ?></div>
                                <h2 class="card-title">
                                    <?= Html::a($post->title, ['post/view', 'slug' => $post->slug]) ?>
                                </h2>
                            </div>
                        <?php endforeach; ?>    
                    </div>
                <?php endif; ?>
                <h3><?= Html::a('MORE '.$category->name.' +', ['post/index', 'category' => $category->slug]) ?></h3>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="spacer-50"></div>
    </div>
</div>