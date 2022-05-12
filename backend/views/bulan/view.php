<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SchoolMonth */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bulan Sekolah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">

    <div class="col-lg-6 col-md-8 col-sm-8">
        <div class="card">
            <header class="card-header">
                <?= Html::a('<i class="fas fa-chevron-left"></i> Back', ['bulan/index'], ['class' => 'btn btn-warning']) ?>
                <div class="filter-block float-right">
                    <?= Html::a('<i class="fas fa-pen"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'onclick' => 'return confirm("Yakin untuk menghapus ini?");',
                    ]) ?>
                </div>
            </header>
            
            <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    //'school_id',
                    'name',
                    //'status',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            </div>
        </div>
    </div>  
</div>
