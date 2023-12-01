<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Student;
use common\models\Presence;
use yii\grid\GridView;

$this->title = 'Presensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<meta http-equiv="refresh" content="600"> <!-- Refresh setiap 600 detik (10 menit) -->
<div class="card">
    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
    <div class="card-header">
        <div class="row">
            <div class="col-lg-4">
                <label>Rentang Tanggal</label><br>
                <div class="input-group input-group-sm">
                    <input type="date" class="form-control" name="startdate" value="<?= $startdate ?>">
                    <input type="date" class="form-control" name="enddate" value="<?= $enddate ?>">
                </div>
            </div>
            <div class="col-lg-2">
                <label>Kelas</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="class">
                        <option value="">Semua</option>
                        <?php
                        foreach ($classes as $cls) {
                            echo '<option ' . ($cls->name == $class ? 'selected' : '') . ' value="' . $cls->name . '">' . $cls->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-4" style="padding-top: 31px;">
                <?= Html::submitButton('<i class="fas fa-search"></i> Tampilkan', ['class' => 'btn btn-success']) ?>
                <?php
                if (Yii::$app->user->identity->role_id == 2) {
                    echo Html::a('<i class="fas fa-plus fa-lg"></i> Import Presensi', ['import'], ['class' => 'btn btn-primary']);
                }
                ?>
                <?php //= Html::a('<i class="fas fa-file-excel"></i> Download Excel', ['export-excel', 'startdate' => $startdate, 'enddate' => $enddate, 'class' => $class], ['class' => 'btn btn-default']) 
                ?>
                <?php //= Html::submitButton('<i class="fas fa-file-pdf"></i> Download PDF', ['class' => 'btn btn-default', 'onclick' => 'alert("Fitur download PDF belum bisa digunakan, menunggu final revisi");']) 
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>

    <div class="card-body p-0">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => '',
            'tableOptions' => [
                'class' => 'table table-striped table-hover'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                ['label' => 'Tanggal', 'value' => function ($model) {
                    return date('d-m-Y', strtotime($model->check_in));
                }],
                ['label' => 'NIS', 'attribute' => 'student.nis'],
                ['label' => 'Nama Siswa', 'attribute' => 'student.name'],
                ['label' => 'Kelas', 'attribute' => 'student.class'],
                ['label' => 'Check In', 'value' => function ($model) {
                    return date('H:i:s', strtotime($model->check_in));
                }],
                ['label' => 'Check Out', 'value' => function ($model) {
                    if ($model->check_out) {
                        return date('H:i:s', strtotime($model->check_out));
                    } else {
                        return '~';
                    }
                }],

            ],
        ]); ?>
    </div>
</div>