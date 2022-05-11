<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Strings;

$baseUrl = Yii::$app->request->baseUrl;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Page', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <header class="card-header">
                <h3 class="card-title">Page Preview</h3>
            </header>
            <div class="card-body">
                <?php
                if ($model->video) {
                    echo '<video autoplay="autoplay" loop="loop" muted="muted" width="100%" pageer="'.Yii::getAlias('@fronturl/images/default-video.webp').'">
                        <source src="'.Yii::getAlias('@fronturl').'/uploads/pages/'.$model->video.'" type="video/mp4" />
                    </video>';
                } else {
                    echo '<img src="'.Yii::getAlias('@fronturl').'/uploads/pages/large-'.$model->image.'" class="img img-responsive page-image" onerror="this.onerror=null; this.src=\''.Yii::getAlias('@fronturl').'/images/default-page.webp\';">';
                }
                ?>
                <br><br>
                <div class="post">
                    <h2 class="m-0 d-inline"><?= $model->title ?></h2>
                </div>
                <div class="post">
                    <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="<?= $baseUrl ?>/images/default-user.png">
                        <span class="username">
                            <a href="#"><?= $model->user->name ?></a>
                        </span>
                        <span class="description">Shared publicly - <?= Strings::timeAgo($model->created_at) ?></span>
                    </div>
                    <p style="font-size: 16px; "><strong><?= $model->description ?></strong></p>
                    <p><?= $model->content ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <?php if ($model->status == 1): ?>
            <div class="alert alert-success">
                <h5 style="margin-bottom: 0px;"><i class="icon fas fa-check"></i> Published</h5>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <h5 style="margin-bottom: 0px;"><i class="icon fas fa-pencil-ruler"></i> Draft</h5>
            </div>
        <?php endif; ?>
        <div class="card">
            <header class="card-header">
                <h3 class="card-title">Information</h3>
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-pen fa-lg"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash fa-lg"></i> Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Are you sure you want to delete this item?");',
                    ]) ?>
                </div>
            </header>
            <div class="card-body">
                <p>
                    <label><i class="fas fa-quote-right"></i> Generated Slug</label><br>
                    <?= $model->slug ?>
                </p>
                <p>
                    <label><i class="fas fa-link"></i> Page URL</label><br>
                    <?= Html::a($model->pageUrl, $model->pageUrl, ['target' => '_blank']) ?>
                </p>
                <p>
                    <label><i class="fas fa-hashtag"></i> Tags</label><br>
                    <?= $model->tagLabel ?>
                </p>
                <p>
                    <label><i class="fas fa-user"></i> Author</label><br>
                    <?= $model->user->name ?>
                </p>
                <p>
                    <label><i class="fas fa-users"></i> Visitor Count</label><br>
                    <?= $model->read ?>
                </p>
            </div>
        </div>
    </div>
</div>