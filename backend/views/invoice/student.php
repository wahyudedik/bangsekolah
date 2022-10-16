<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\School;

$this->title = 'Tagihan';
$this->params['breadcrumbs'][] = $this->title;

$school = School::findOne(Yii::$app->session->get('school_id'));
$user = Yii::$app->user->identity;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <label style="margin-right: 10px;">Pilih Kelas</label>
                    <select name="class" class="form-control">
                        <option value="ALL">Semua Kelas</option>
                        <?php
                        foreach ($classes as $cls) {
                            echo '<option value="'.$cls->name.'" '.($cls->name == $class ? 'selected' : '').'>'.$cls->name.'</option>';
                        }
                        ?>
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Cari NIS/NISN/Nama ..." value="<?= $search ?>">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?php if (count($school->students)>0 && ($user->role_id == 2 || $user->role_id == 3)): ?>
                        <?= Html::a('<i class="fas fa-plus fa-lg"></i> Buat Tagihan', ['create'], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary'=> '',
                'tableOptions' => [
                    'class' => 'table table-striped table-hover'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'nis',
                    'name',
                    'class',
                    'year',
                    [
                        'label' => 'Tagihan',
                        'format' => 'raw',
                        'value' => function($model) {
                            $paid = 0;
                            $inv = 0;
                            foreach ($model->invoices as $invoice) {
                                $inv++;
                                if ($invoice->status == 'PAID') {
                                    $paid++;
                                }
                            }
                            if ($paid == 0 && $inv > 0) {
                                return '<span class="text-danger">'.$paid.'/'.$inv.'</span>';
                            } else if ($inv == $paid && $inv == 0) {
                                return '<span class="text-success">'.$paid.'/'.$inv.'</span>';
                            } else if ($inv > $paid) {
                                return '<span class="text-warning">'.$paid.'/'.$inv.'</span>';
                            } else {
                                return '<span class="text-success">'.$paid.'/'.$inv.'</span>';
                            }
                        }
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Daftar Tagihan',
                        'template' => '{view}', 
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', ['invoice/invoices', 'student' => $model->id], ['class' => 'btn btn-success']).' ';
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
