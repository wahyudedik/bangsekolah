<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\Teacher;

$this->title = 'Presensi Pelajaran';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                Riwayat Presensi
                <!-- <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Search ..." value="<?= $q ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?> -->
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-file-excel"></i> Import Excel', ['import'], ['class' => 'btn btn-success']) ?>
                    <?php echo Html::a('<i class="fas fa-file-excel"></i> Download Excel', ['lesson-schedule/export'], ['class' => 'ml-2 btn btn-info']) . ' '; ?>
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

                    // 'schoolClass.name',
                    ['label' => 'Tanggal', 'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->date));
                    }],
                    'hour',
                    'class',
                    'lesson.name',
                    ['attribute' => 'teacher.name', 'label' => 'Guru'],
                    ['attribute' => 'attendanceCount', 'label' => 'Jumlah Hadir'],
                    //'status',
                    //'created_at',
                    //'updated_at',
                    //'created_by',
                    //'updated_by',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view}',
                        'contentOptions' => ['style' => 'width:150px;'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $teacher = Teacher::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
                                $teacher_id = null;
                                if ($teacher) {
                                    $teacher_id = $teacher->id;
                                }
                                $btnClass = 'btn btn-success';
                                if (date('Y-m-d') == $model->date && $model->teacher->id == $teacher_id) {
                                    $btnClass = 'btn btn-warning';
                                }
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => $btnClass]) . ' ';
                            },
                            // 'delete' => function ($url, $model) {
                            //     return Html::a('<i class="fas fa-trash"></i>', $url, ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure?', 'data-method' => 'post']) . ' ';
                            // },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>