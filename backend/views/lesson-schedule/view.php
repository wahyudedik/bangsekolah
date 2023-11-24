<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->teacher->name . ' (' . $model->lesson->name . ')';
$this->params['breadcrumbs'][] = ['label' => 'Presensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card">
    <header class="card-header">
        Presensi Kelas Pelajaran
    </header>
    <header class="card-header">
        <dl class="row">
            <dt class="col-sm-4">Tanggal</dt>
            <dd class="col-sm-8"><?= date('d-m-Y', strtotime($model->date)) ?></dd>
            <dt class="col-sm-4">Mata Pelajaran</dt>
            <dd class="col-sm-8"><?= $model->lesson->name ?></dd>
            <dt class="col-sm-4">Kelas</dt>
            <dd class="col-sm-8"><?= $model->class ?></dd>
            <dt class="col-sm-4">Nama Pengajar</dt>
            <dd class="col-sm-8"><?= $model->teacher->name ?></dd>
            <dt class="col-sm-4">Jam Pelajaran</dt>
            <dd class="col-sm-8"><?= $model->hour ?></dd>
        </dl>
    </header>
    <div class="card-body p-0">
        <table class="table">
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Presensi</th>
                <th>Catatan</th>
            </tr>
            <?php
            $i = 1;
            foreach ($model->lessonScheduleAttendances as $att) :
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $att->student->nis ?></td>
                    <td><?= $att->student->name ?></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="card-footer">
        Simpan
    </div>
</div>