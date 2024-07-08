<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Strings;
use common\helpers\Visitor;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Article';
?>
<main id="main">
    <section>
        <div class="container">
            <div class="spacer-50"></div>
            <div class="row">
                <div class="col-md-8">
                    <?php if ($category) { ?>
                        <!-- <h3 style="margin-top: 20px; overflow-wrap: break-word;"><?= $category->name ?></h3> -->
                        <p style="overflow-wrap: break-word;">
                            <?php
                            foreach ($category->childs as $child) {
                                echo Html::a('<span style="font-size: 20px; margin-right: 20px;">' . $child->name . '</span>', ['post/index', 'category' => $child->slug], ['class' => 'child-category']);
                            }
                            ?>
                        </p>
                        <div class="spacer-30"></div>
                        <h5 class="text-muted">Menampilkan artikel dengan kategori "<?= $category->name ?>"</h5>
                    <?php } else { ?>
                        <!-- <h3 class="mt-3">All Categories</h3> -->
                        <br>
                        <p>
                            <?php
                            foreach ($categories as $cat) {
                                echo Html::a('<span style="font-size: 17px; margin-right: 20px; margin-top: ">' . $cat->name . '</span>', ['post/index', 'category' => $cat->slug], ['class' => 'child-category']);
                            }
                            ?>
                        </p>
                    <?php } ?>
                    <div class="spacer-30"></div>
                    <?php foreach ($posts as $post) : ?>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <!-- Image -->
                                <?= Html::a('<img src="' . $baseUrl . '/uploads/posts/small-' . $post->image . '" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\'' . Yii::getAlias('@fronturl') . '/images/default-post.webp\';">', ['post/view', 'slug' => $post->slug]); ?>
                            </div>
                            <div class="col-md-6">
                                <!-- Title & Info -->
                                <div style="margin-top: 10px; margin-bottom: 0px;font-size: 12pt;"><?= strtoupper($post->postCategory->name) ?></div>
                                <h2 style="margin-top: 5px; overflow-wrap: break-word;">
                                    <?= Html::a($post->title, ['post/view', 'slug' => $post->slug]) ?>
                                </h2>
                                <div style="margin-bottom: 0px;font-size: 12pt;" class="hidden-mobile"><?= substr($post->description, 0, 100) . '...' ?></div>
                                <div style="margin-bottom: 0px;">
                                    <?= strtoupper($post->createdBy ? $post->createdBy->name : 'SMedia Newsdesk') ?> |
                                    <?= date('d M Y', strtotime($post->created_at)) ?>
                                </div>
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="spacer-30 hidden-desktop"></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>