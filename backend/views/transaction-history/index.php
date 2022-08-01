<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Invoice;
use common\models\Transaction;
use common\helpers\Strings;

$this->title = 'Riwayat Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <h5>Filter Data</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
        <div class="row">
            <div class="col-lg-3">
                <label>Rentang Tanggal</label><br>
                <div class="input-group input-group-sm">
                    <input type="date" class="form-control" name="startdate" value="<?= $startdate ?>">
                    <input type="date" class="form-control" name="enddate" value="<?= $enddate ?>">                    
                </div>
            </div>
            <div class="col-lg-2">
                <label>Tahun Ajaran</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="year">
                        <option value="">-- Semua --</option>
                        <?php 
                        foreach ($years as $yr) {
                            echo '<option '.($yr->name == $year ? 'selected' : '').' value="'.$yr->name.'">'.$yr->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <label>Kelas</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="class">
                        <option value="">-- Semua --</option>
                        <?php 
                        foreach ($schoolClasses as $cls) {
                            echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <label>Nama Pembayaran</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="payment">
                        <option value="">-- Semua --</option>
                        <?php 
                        foreach ($schoolPayments as $pymnt) {
                            echo '<option '.($pymnt->number == $payment ? 'selected' : '').' value="'.$pymnt->number.'">'.$pymnt->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <label>Metode Pembayaran</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="method">
                        <option value="">-- Semua --</option>
                        <option value="CASH" <?= $method == 'CASH' ? 'selected' : '' ?>>Tunai</option>
                        <option value="TRANSFER" <?= $method == 'TRANSFER' ? 'selected' : '' ?>>Transfer</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <?php
        echo Html::submitButton('<i class="fas fa-search"></i> Tampilkan', ['class' => 'btn btn-success']).' ';
        // if ($class && $payment) {
            echo Html::a('<i class="fas fa-sync-alt"></i> Reset', ['payment-recap/index'], ['class' => 'btn btn-primary']).' ';
            echo Html::a('<i class="fas fa-print"></i> Download PDF', ['payment-recap/download', 'startdate' => $startdate, 'enddate' => $enddate, 'year' => $year, 'class' => $class, 'payment' => $payment, 'method' => $method], ['class' => 'btn btn-info', 'download' => 'payment-recap-'.$startdate.'-'.$enddate.'.pdf']).' ';
            echo Html::a('<i class="fas fa-file-excel"></i> Export Excel', ['payment-recap/export', 'startdate' => $startdate, 'enddate' => $enddate, 'year' => $year, 'class' => $class, 'payment' => $payment, 'method' => $method], ['class' => 'btn btn-info']).' ';
        // }
        ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Riwayat Transaksi</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered">
            <tr style="background: #efefef;">
                <th rowspan="2">NO</th>
                <th rowspan="2">TANGGAL</th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">NAMA SISWA</th>
                <th rowspan="2">NAMA PEMBAYARAN</th>
                <th rowspan="2">KELAS</th>
                <th colspan="2">JENIS TRANSAKSI</th>
                <th rowspan="2">JUMLAH</th>
            </tr>
            <tr style="background: #efefef;">
                <th>Tunai</th>
                <th>Transfer</th>
            </tr>
            <?php
            $i=1;
            $totalCash = 0;
            $totalTransfer = 0;
            foreach ($dataProvider->models as $trans):
                if ($trans->payment_method == 'CASH') {
                    $totalCash += $trans->amount;
                } else {
                    $totalTransfer += $trans->amount;
                }
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= Strings::getFullDate($trans->date) ?></td>
                    <td><?= $trans->student->nis ?></td>
                    <td><?= $trans->student->name ?></td>
                    <td><?= $trans->invoice->description ?></td>
                    <td><?= $trans->student->class ?></td>
                    <td style="text-align: right;"><?= $trans->payment_method == 'CASH' ? $trans->amountRupiah : '' ?></td>
                    <td style="text-align: right;"><?= $trans->payment_method == 'TRANSFER' ? $trans->amountRupiah : '' ?></td>
                    <td style="text-align: right;"><?= $trans->amountRupiah ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="6">TOTAL</th>
                <th style="text-align: right;"><?= Strings::getRupiah($totalCash) ?></th>
                <th style="text-align: right;"><?= Strings::getRupiah($totalTransfer) ?></th>
                <th style="text-align: right;"><?= Strings::getRupiah($totalCash + $totalTransfer) ?></th>
            </tr>
        </table>
    </div>
</div>

<style>
th, td {
    white-space: nowrap;
}
</style>