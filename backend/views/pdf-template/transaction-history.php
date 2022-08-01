<?php
use common\models\Invoice;
use common\helpers\Strings;
$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="card">
    <h4 align="center">
        <b>DAFTAR PENERIMAAN PEMBAYARAN SISWA</b><br>
        <b><?= strtoupper($school->name) ?></b><br>
        <b>TAHUN <?= $year ?></b><br>
    </h4>
    <div class="title-body" style="padding-bottom: 15px">
        <table>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td><?= Strings::getFullDate($startdate) ?> s/d <?= Strings::getFullDate($enddate) ?></td>
            </tr>
        </table>
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
    <div class="footer-body">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%"></td>
                <td style="width: 30%" align="center">
                    <span><?= $school->city.', '.Strings::getFullDate(date('Y-m-d')) ?></span><br />
                    <span>Mengetahui</span><br /><br /><br />
                    <span>(...............)</span>
                </td>
            </tr>
        </table>
    </div>
</div>