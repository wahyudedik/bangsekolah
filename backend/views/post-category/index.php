<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">

                    </div>
                    <div class="col-lg-6">
                        <div class="card-tools float-right">
                            <?=  Html::a('<i class="fas fa-plus fa-lg"></i> Create Category', ['create'], ['class' => 'btn btn-success']) ?>
                            <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NUMBER</th>
                                <th>CATEGORY</th>
                                <th>POST COUNT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; foreach ($parents as $parent) :?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><strong><?= strtoupper($parent->name) ?></strong></td>
                                    <td><?= count($parent->posts) ?></td>
                                    <td>
                                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $parent->id], ['class' => 'btn btn-success']) ?>
                                        <?= Html::a('<i class="fas fa-pen"></i>', ['update', 'id' => $parent->id], ['class' => 'btn btn-primary']) ?>
                                        <?= count($parent->posts) == 0 && count($parent->childs) == 0 ? Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $parent->id], ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']) : '' ?>
                                    </td>
                                </tr>
                                <?php foreach ($parent->childs as $child): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><span style="padding-left: 30px;">-></span> <?= $child->name ?></td>
                                        <td><?= count($child->posts) ?></td>
                                        <td>
                                            <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $child->id], ['class' => 'btn btn-success']) ?>
                                            <?= Html::a('<i class="fas fa-pen"></i>', ['update', 'id' => $child->id], ['class' => 'btn btn-primary']) ?>
                                            <?= count($child->posts) == 0 ? Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $child->id], ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']) : '' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>