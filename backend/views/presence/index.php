<?php

use common\models\LessonSchedule;
use common\models\LessonScheduleAttendance;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\School;
use common\models\Student;
use common\models\Presence;

$this->title = 'Presensi Harian';
$this->params['breadcrumbs'][] = $this->title;

$school_id = School::findOne(Yii::$app->session->get('school_id'));
?>
<meta http-equiv="refresh" content="300"> <!-- Refresh setiap 300 detik (5 menit) -->
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
                <?php //= Html::a('<i class="fas fa-file-excel"></i> Download Excel', ['export-excel', 'startdate' => $startdate, 'enddate' => $enddate, 'class' => $class], ['class' => 'btn btn-default']) 
                ?>
                <?php //= Html::submitButton('<i class="fas fa-file-pdf"></i> Download PDF', ['class' => 'btn btn-default', 'onclick' => 'alert("Fitur download PDF belum bisa digunakan, menunggu final revisi");']) 
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
    <div class="card-body p-0">
        <?php if ($class) : ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr style="background: #efefef;">
                        <th rowspan="2">NO</th>
                        <th rowspan="2" width="300px">NAMA LENGKAP</th>
                        <th colspan="3" style="text-align: center;">KEHADIRAN</th>
                        <th rowspan="2" style="width: 140px;">% KEHADIRAN</th>
                        <th rowspan="2" colspan="4">KETERANGAN</th>
                    </tr>
                    <tr style="background: #efefef;">
                        <th>MASUK</th>
                        <th>ABSEN</th>
                        <th>JUMLAH HARI</th>
                    </tr>
                    <?php
                    $i = 1;
                    $students = Student::find()->where(['class' => $class, 'school_id' => $school_id, 'status' => 'ACTIVE'])->all();
                    $totalPersentase = 0;
                    $totalSiswa = 0;

                    // $totalHari = $startdate - $enddate;

                    foreach ($students as $student) {
                        $totalSiswa++;
                        // kolom event student
                        $jumlahMasuk = 0;
                        $jumlahAbsen = 0;

                        echo '<tr>';
                        echo '<td>' . $i++ . '</td>';
                        echo '<td>' . $student->name . '</td>';

                        // menghitung total masuk
                        $jumlahMasuk = Presence::find()
                            ->where(['school_id' => $school_id, 'class' => $class])
                            ->andWhere(['student_id' => $student->id])
                            ->andWhere(['>=', 'date', $startdate])
                            ->andWhere(['<=', 'date', $enddate])
                            ->count();
                        $jumlahAbsen = $totalHari - $jumlahMasuk;


                        // kolom jumlah
                        echo '<td>' . $jumlahMasuk . '</td>';
                        echo '<td>' . $jumlahAbsen . '</td>';
                        echo '<td>' . $totalHari . '</td>';

                        // persentase
                        if ($totalHari > 0) {
                            $persentase = round(($jumlahMasuk / $totalHari) * 100);
                        } else {
                            $persentase = 0;
                        }
                        $totalPersentase += $persentase;
                        if ($persentase >= 50) {
                            $color = '#BFFCC6'; //green
                        } else if ($persentase < 50 && $persentase > 30) {
                            $color = '#FFF5BA'; //kuning
                        } else {
                            $color = '#FFC9DE'; //red
                        }
                        echo '<td style="background: ' . $color . '">' . $persentase . '%</td>';
                        echo '<td>-</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
                <table style="margin: 20px;">
                    <tr>
                        <td style="width: 20px; background: #BFFCC6;"></td>
                        <td></td>
                        <td>Kehadiran > 50%</td>
                    </tr>
                    <tr>
                        <td style="width: 20px; background: #FFF5BA;"></td>
                        <td></td>
                        <td>Kehadiran 30% - 50%</td>
                    </tr>
                    <tr>
                        <td style="width: 20px; background: #FFC9DE;"></td>
                        <td></td>
                        <td>Kehadiran < 30%</td>
                    </tr>
                </table>
                <div style="margin: 20px;">
                    Kehadiran rata-rata peserta: <br>
                    <?php
                    if ($totalSiswa > 0) {
                        $percentage = round($totalPersentase / ($totalSiswa * 100) * 100);
                    } else {
                        $percentage = 0;
                    }
                    ?>
                    <span style="font-size: 70px;"><?= $percentage ?>%</span>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-info" style="margin-left: 20px; margin-right: 20px; margin-top: 20px;">Silakan pilih tanggal dan kelas</div>
        <?php endif; ?>
    </div>
</div>