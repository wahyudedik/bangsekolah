<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Presence;

$this->title = 'Presensi Manual Harian';

$arrNamaHari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
$hariIni = date('w');
?>

<?php $form = ActiveForm::begin(); ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3">
                <label for="">Hari / Tanggal</label>
                <h4><?= $arrNamaHari[$hariIni] . ', ' . date('d M Y', strtotime($date)) ?></h4>
            </div>
            <div class="col-lg-3">
                <div class="input-group input-group-sm">
                    <select class="form-control" name="class" id="class">
                        <option value="">Semua</option>
                        <?php
                        foreach ($classes as $cls) {
                            echo '<option ' . ($cls->name == $class->name ? 'selected' : '') . ' value="' . $cls->name . '">' . $cls->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan Presensi', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-file-alt"></i> Lihat Rekapitulasi', ['presence/index', 'class' => $class->name], ['class' => 'btn btn-info']) ?>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h4>Presensi Kelas <?= $class->name ?></h4>
        <table class="table">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">Nama Siswa</th>
                <th colspan="3">Regular</th>
                <th colspan="3">Fullday</th>
            </tr>
            <tr>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th><input type="checkbox" id="check-all"> Pilih Semua</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th><input type="checkbox" id="check-all-fullday"> Pilih Semua</th>
            </tr>
            <?php
            $i = 1;
            foreach ($students as $student) :
                // query presensi untuk siswa tersebut pada hari ini
                $presence = Presence::find()
                    ->where(['student_id' => $student->id, 'date' => date('Y-m-d')])
                    ->andWhere(['not', ['status_check_in' => null]])->one();
                $presenceFullDay = Presence::find()
                    ->where(['student_id' => $student->id, 'date' => date('Y-m-d')])
                    ->andWhere(['not', ['status_check_out_fullday' => null]])->one();
            ?>
                <tr>
                    <td><?= $i++  ?></td>
                    <td><?= $student->nis  ?></td>
                    <td><?= $student->name  ?></td>
                    <td><?= $check_in  ?></td>
                    <td><?= $check_out  ?></td>
                    <td><input type="checkbox" name="students[]" class="check-student" value="<?= $student->id ?>" <?= $presence ? 'checked' : '' ?>></td>
                    <td><?= $check_in_fullday  ?></td>
                    <td><?= $check_out_fullday  ?></td>
                    <td><input type="checkbox" name="studentsfullday[]" class="check-student-fullday" value="<?= $student->id ?>" <?= $presenceFullDay ? 'checked' : '' ?>></td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    // check all students
    $('#check-all').click(function() {
        if ($('#check-all').is(':checked')) {
            $('.check-student').attr('checked', true);
        } else {
            $('.check-student').attr('checked', false);
        }
    });
    $('#check-all-fullday').click(function() {
        if ($('#check-all-fullday').is(':checked')) {
            $('.check-student-fullday').attr('checked', true);
        } else {
            $('.check-student-fullday').attr('checked', false);
        }
    });

    $('#class').change(function() {
        const selectedClass = $(this).val();
        window.location.href = window.location.pathname + "?r=presence/manual&class=" + selectedClass;
    });
</script>