<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

$this->title = $model->teacher->name . ' (' . $model->lesson->name . ')';
$this->params['breadcrumbs'][] = ['label' => 'Presensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?php $form = ActiveForm::begin(['id' => 'form-presence', 'action' => ['save-presence', 'id' => $model->id], 'method' => 'post']) ?>
<div class="card">
    <header class="card-header">
        <div class="float-right">
            <button id="btn-submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Presensi</button>
        </div>
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
                <th><input type="checkbox" id="check-all"> Kehadiran</th>
                <th>Catatan</th>
            </tr>
            <?php
            $i = 1;
            foreach ($model->lessonScheduleAttendances as $scheduleAttendance) :
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $scheduleAttendance->student->nis ?></td>
                    <td><?= $scheduleAttendance->student->name ?></td>
                    <td><input type="checkbox" name="student[]" class="check-student" value="<?= $scheduleAttendance->id ?>" <?= $scheduleAttendance->status == 'MASUK' ? 'checked' : '' ?>></td>
                    <td>
                        <select name="note-<?= $scheduleAttendance->id ?>">
                            <option></option>
                            <?php
                            $note1 = '';
                            $note2 = '';
                            $note3 = '';
                            if ($scheduleAttendance->status == 'MASUK') {
                                if ($scheduleAttendance->note == 'IZIN') {
                                    $note1 = 'selected';
                                } else if ($scheduleAttendance->note == 'PULANG') {
                                    $note2 = 'selected';
                                } else if ($scheduleAttendance->note == 'TANPA KETERANGAN') {
                                    $note3 = 'selected';
                                }
                            } else {
                                if ($scheduleAttendance->note == 'IZIN') {
                                    $note1 = 'selected';
                                } else if ($scheduleAttendance->note == 'PULANG') {
                                    $note2 = 'selected';
                                } else if ($scheduleAttendance->note == 'TANPA KETERANGAN') {
                                    $note3 = 'selected';
                                }
                            }
                            ?>
                            <option value="IZIN" <?= $note1 ?>>IZIN</option>
                            <option value="PULANG" <?= $note2 ?>>PULANG</option>
                            <option value="TANPA KETERANGAN" <?= $note3 ?>>TANPA KETERANGAN</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="card-footer">
        <!-- <div class="form-group">
            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
        </div> -->
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    // check all students
    $('#check-all').click(function() {
        if ($('#check-all').is(':checked')) {
            $('.check-student').attr('checked', true);
        } else {
            $('.check-student').attr('checked', false);
        }
    });

    // submit button
    var submitClicked = false;
    $("#btn-submit").click(function(e) {
        if (submitClicked == false) {
            if (confirm('Apakah Anda yakin simpan kehadiran?')) {
                $('#form-presence').submit();
                setTimeout(function() {
                    $('#btn-submit').trigger('click');
                }, 500)
            }
        }
        submitClicked = true;
    });
</script>