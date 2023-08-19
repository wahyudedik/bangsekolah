<?php
use common\models\Invoice;
use common\helpers\Strings;
$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="card">
    <div class="card-header">
        <div class="card-header" style="width: 100%; border-bottom: 5px solid black; margin-bottom: 25px;  ">
            <table style="width: 100%">
                <tr>
                    <td style="width:10%">
                        <?php if ($school->image == null) { ?>
                            <img src="<?= $baseUrl ?>/images/banksekolah-logo.png" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8; width: 80px;" />
                        <?php } else { ?>
                            <img src="<?= $baseUrl ?>/uploads/school/<?= $school->image ?>" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8; width: 80px;" />
                        <?php } ?>
                    </td>
                    <td style="width:80%" align="center">
                        <h4><b><?= $school->name ?></b></h4><br>
                        <p><?= 'Alamat: '.$school->address.', '.$school->city ?></p>
                        <p><?= 'Telp: '.$school->phone ?></p>
                    </td>
                    <td style="width:10%"></td>
                </tr>
            </table>
        </div>
    </div>
    <h4 align="center"><b>Riwayat Transaksi</b></h4>
    <div class="title-body" style="margin-top: 50px; padding-bottom: 15px">
        <table>
            <tr>
                <td><strong>NIS</strong></td>
                <td>:</td>
                <td><?= $student->nis ?></td>
                <td></td>
                <td><strong>Nomor Invoice</strong></td>
                <td>:</td>
                <td><?= $invoice->number ?></td>
            </tr>
            <tr>
                <td><strong>Nama Siswa</strong></td>
                <td>:</td>
                <td><?= $student->name ?></td>
                <td></td>
                <td><strong>Jumlah</strong></td>
                <td>:</td>
                <td><?= Strings::getRupiah($invoice->amount) ?></td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>:</td>
                <td><?= $student->class ?></td>
                <td></td>
                <td><strong>Telah Dibayarkan</strong></td>
                <td>:</td>
                <td><?= $invoice->paidAmountRupiah ?></td>
            </tr>
            <tr>
                <td><strong>Tahun Ajaran</strong></td>
                <td>:</td>
                <td><?= $student->year ?></td>
                <td></td>
                <td><strong>Sisa Pembayaran</strong></td>
                <td>:</td>
                <td><?= $invoice->remainingRupiah ?></td>
            </tr>
        </table>
    </div>
    
    <div class="card-body p-0">
        <table class="table table-bordered table-responsive" style="font-size: 12px">
            <tr>
                <th>No</th>
                <th>Nomor Transaksi</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Metode Pembayaran</th>
                <th>Catatan</th>
                <th>Admin</th>
            </tr>
            <?php
            $i=1;
            foreach ($transactions as $transaction):
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $transaction->number ?></td>
                    <td><?= $transaction->date ?></td>
                    <td><?= Strings::getRupiah($transaction->amount) ?></td>
                    <td><?= $transaction->payment_method ?></td>
                    <td><?= $transaction->note ?></td>
                    <td><?= $transaction->user->name ?></td>
                </tr>
            <?php endforeach; ?>
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