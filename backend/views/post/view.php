<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;

$baseUrl = Yii::$app->request->baseUrl;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Post', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <header class="card-header">
                <h3 class="card-title">Post Preview</h3>
            </header>
            <div class="card-body">
                <?php
                if (!empty($model->video)) {
                    echo '<video type="video/mp4" autoplay="autoplay" loop="loop" muted="muted" width="100%" poster="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                        <source src="'.Yii::getAlias('@fronturl').'/uploads/posts/'.$model->video.'" type="video/mp4" />
                    </video>';
                } else {
                    echo '<img src="'.Yii::getAlias('@fronturl').'/uploads/posts/large-'.$model->image.'" class="img img-responsive post-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-post.webp\';">';
                }
                ?>
                <br><br>
                <div class="post">
                    <h2 class="m-0 d-inline"><?= $model->title ?></h2>
                    <h6 class="m-0 align-midle text-secondary">Category: <?= $model->postCategory->name ?></h6>
                </div>
                <div class="post">
                    <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="<?= $baseUrl ?>/images/default-user.png">
                        <span class="username">
                            <a href="#"><?= $model->createdBy->name ?></a>
                        </span>
                        <span class="description">Shared publicly - <?= Strings::timeAgo($model->created_at) ?></span>
                    </div>
                    <p style="font-size: 16px; "><strong><?= $model->description ?></strong></p>
                    <p><?= $model->content ?></p>
                </div>
            </div>
        </div>
        <!-- <div class="card">
            <header class="card-header">
                <h3 class="card-title">Post Images</h3>
            </header>
            <div class="card-body">
                <?= Html::a('<i class="fas fa-image fa-lg"></i> Add Image', ['add-image', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <br><br>
                <div class="row">
                <?php
                $i = 0;
                foreach ($model->postImages as $image) {
                    if ($i%3==0) {
                        echo '</div><br><div class="row">';
                    }
                    echo '<div class="col-md-4">';
                    echo '<img src="'.Yii::getAlias('@fronturl').'/uploads/post-images/large-'.$image->image.'" class="img img-responsive img-rounded">';
                    echo $image->description.'<br>';
                    echo Html::a('<i class="fas fa-trash"></i> Delete', ['post/delete-image', 'id' => $image->id], ['class' => 'text-red', 'onclick' => 'return confirm("Are you sure?");']);
                    echo '</div>';
                    $i++;
                }
                ?>
                </div>
            </div>
        </div> -->
    </div>
    <div class="col-lg-3">
        <?php if ($model->status == 'ACTIVE'): ?>
            <div class="alert alert-success">
                <h5 style="margin-bottom: 0px;"><i class="icon fas fa-check"></i> Published</h5>
            </div>
        <?php else: ?>
            <?php if ($model->scheduled_at !== null): ?>
                <div class="alert alert-warning">
                    <h5 style="margin-bottom: 0px;"><i class="icon fas fa-pencil-ruler"></i> Draft</h5>
                    <p style="margin-bottom: 0px;">Will be published on <?= $model->scheduled_at ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <h5 style="margin-bottom: 0px;"><i class="icon fas fa-pencil-ruler"></i> Draft</h5>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="card">
            <header class="card-header">
                <h3 class="card-title">Information</h3>
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-pen fa-lg"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php
                    if ($model->created_by == Yii::$app->user->identity->id || Yii::$app->user->identity->role_id == 1) {
                        echo Html::a('<i class="fas fa-trash fa-lg"></i>', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'onclick' => 'return confirm("Are you sure you want to delete this item?");',
                        ]);
                    }
                    ?>
                </div>
            </header>
            <div class="card-body">
                <p>
                    <label><i class="fas fa-quote-right"></i> Generated Slug</label><br>
                    <?= $model->slug ?>
                </p>
                <p>
                    <label><i class="fas fa-link"></i> Post URL</label><br>
                    <?= Html::a($model->postUrl, $model->postUrl, ['target' => '_blank']) ?>
                </p>
                <p>
                    <label><i class="fas fa-tags"></i> Category</label><br>
                    <?= $model->postCategory->name ?>
                </p>
                <p>
                    <label><i class="fas fa-hashtag"></i> Tags</label><br>
                    <?= $model->tagLabel ?>
                </p>
                <p>
                    <label><i class="fas fa-user"></i> Author</label><br>
                    <?= $model->createdBy->name ?><br><?= $model->created_at ?>
                </p>
                <p>
                    <label><i class="fas fa-user"></i> Updated By</label><br>
                    <?= $model->updatedBy->name ?><br><?= $model->updated_at ?>
                </p>
                <p>
                    <label><i class="fas fa-calendar-alt"></i> Published Date</label><br>
                    <?= $model->publishedDate ?>
                </p>
                <p>
                    <label><i class="fas fa-users"></i> Visitor Count</label><br>
                    <?= $model->read ?>
                </p>
            </div>
        </div>
    </div>
</div>
