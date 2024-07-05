<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\User;

$role_id = Yii::$app->user->identity->role_id;

$this->title = 'Post';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">
                        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                        <div class="input-group input-group-sm">
                            <select class="form-control" name="status">
                                <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                                <option value="published" <?= $status == 'published' ? 'selected' : '' ?>>Diterbitkan</option>
                                <option value="draft" <?= $status == 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="scheduled" <?= $status == 'scheduled' ? 'selected' : '' ?>>Terjadwal</option>
                            </select>
                            <?php if ($role_id !== 2): ?>
                            <select class="form-control" name="author">
                                <option value="" <?= $author == '' ? 'selected' : '' ?>>Semua Author</option>
                                <?php
                                foreach (User::find()->where(['status' => 'ACTIVE'])->all() as $user) {
                                    echo '<option value="'.$user->name.'" '.($author == $user->name ? 'selected' : '').'>'.$user->name.'</option>';
                                }
                                ?>
                            </select>
                            <?php endif; ?>        
                            <input type="text" name="title" class="form-control" placeholder="Judul" value="<?=  $title ?>">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                            </span>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-tools float-right">
                            <?= Html::a('<i class="fas fa-cubes fa-lg"></i> Kategori Post', ['post-category/index'], ['class' => 'btn btn-primary']); ?>
                            <?= Html::a('<i class="fas fa-plus fa-lg"></i> Tambah Post Baru', ['create'], ['class' => 'btn btn-success']) ?>
                            <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'summary' => '',
                            'tableOptions' => [
                                'class' => 'table table-striped table-hover'
                            ],
                            'pager' => [
                                'firstPageLabel' => 'First',
                                'lastPageLabel'  => 'Last'
                            ],
                            // 'rowOptions'=>function($model){
                            //     if($model->status == 0){
                            //         return ['class' => 'draft'];
                            //     }
                            // },
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                ['attribute' => 'imageSmall', 'format' => 'raw'],
                                'titleSort',
                                ['attribute' => 'postCategory.name', 'label' => 'Category'],
                                'read',
                                ['attribute' => 'createdBy.name', 'label' => 'Author'],
                                //'statusLabel',
                                'status',
                                'publishedDate',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Action',
                                    'template' => '{view}{update}', 
                                    'contentOptions' => ['style' => 'width:150px;'],
                                    'buttons' => [
                                        'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                                        'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                                        // 'delete' => function ($url, $model) { return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']).' '; },
                                    ]
                                ],
                            ],
                        ]); ?>
                </div>
            </div>
        </div>
        <?php // Html::a('Post Category', ['post-category/index'], ['class' => 'btn btn-primary']); ?>
    </div>
</div>
