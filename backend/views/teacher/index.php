<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\School;

$this->title = 'Guru';
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
                    <?php if ($school): ?>
                        <?php if (count($school->schoolPayments)>0 && count($school->schoolMonths)>0 && count($school->schoolClasses)>0 && ($user->role_id == 2 || $user->role_id == 3)): ?>
                            <?= Html::a('<i class="fas fa-plus fa-lg"></i> Tambah Guru', ['create'], ['class' => 'btn btn-success']) ?>
                            <?= Html::a('<i class="fas fa-upload fa-lg"></i> Import', ['import'], ['class' => 'btn btn-primary']) ?>
                        <?php endif; ?>
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

                    'nik',
                    'name',
                    'class',
                    'gender',
                    'year',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Detail',
                        'template' => '{view}', 
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) { return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-success']).' '; },
                            'update' => function ($url, $model) { return Html::a('<i class="fas fa-pen"></i>', $url, ['class' => 'btn btn-primary']).' '; },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Apakah Anda yakin hapus data ini?");']).' ';
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
