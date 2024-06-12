<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Backup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                Download backup database Banksekolah
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <!--
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Search ..." value="<?= $q ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                -->
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?>
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
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'createdAt',
                    ['attribute' => 'download', 'format' => 'raw'],
                    //'updated_at',

                ],
            ]); ?>
        </div>
    </div>