<?php
use common\helpers\Strings;

$transactionNumber = '';
$transactionDate = '';
foreach($transactions as $trx) {
    $transactionNumber = $trx->number;
    $transactionDate = date('d/m/Y', strtotime($trx->date));
    break;
}
$baseUrl = Yii::$app->request->baseUrl;
?>
<div style="color: #000000;">
    
    <!-- <watermarkimage src="images/watermark-sertifikat-provinsi.png" size="285, 200" alpha="1" /> -->
    <table cellpadding="5px" width="100%">
        <tr>
            <td width="100px">
                <?php if ($transaction->invoice->school->image == null) { ?>
                    <img src="<?= $baseUrl ?>/images/banksekolah-logo.png" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8; width: 80px; height: 80px;" />
                <?php } else { ?>
                    <img src="<?= $baseUrl ?>/uploads/school/<?= $transaction->invoice->school->image ?>" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8; width: 80px; height: 80px;" />
                <?php } ?>
            </td>
            <td style="text-align: left; font-family: monospace;">
                <span style="font-size: 20px;"><b><?= strtoupper($transaction->invoice->school->name) ?></b></span><br>
                <?= $transaction->invoice->school->address.' '.$transaction->invoice->school->city ?><br>
                <?= $transaction->invoice->school->phone ?><br>
            </td>
        </tr>
    </table>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table width="100%">
                        <tr>
                            <td><strong>NIS</strong></td>
                            <td>:</td>
                            <td style="font-family: monospace;"><?= $transaction->student->nisn ?></td>
                            <td></td>
                            <td><strong>No Transaksi</strong></td>
                            <td>:</td>
                            <td style="font-family: monospace;"><?= $transactionNumber ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>:</td>
                            <td style="font-family: monospace;"><?= $transaction->student->name ?></td>
                            <td></td>
                            <td><strong>Tanggal</strong></td>
                            <td>:</td>
                            <td style="font-family: monospace;"><?= $transactionDate ?></td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>:</td>
                            <td style="font-family: monospace;"><?= $transaction->student->class ?></td>
                            <td></td>
                            <td><strong>Tahun Pelajaran</strong></td>
                            <td>:</td>
                            <td style="font-family: monospace;"><?= $transaction->student->year ?></td>
                        </tr>
                    </table>
                    <br><br>
                    Dengan rincian pembayaran sebagai berikut:                   
                    <br>
                    <table width="100%" cellspacing="0" cellpadding="5" class="table table-bordered">
                        <tr class="border-top">
                            <th>NO</th>
                            <th>NAMA PEMBAYARAN</th>
                            <th>JENIS PEMBAYARAN</th>
                            <th>TAGIHAN</th>
                            <th>JUMLAH PEMBAYARAN</th>
                        </tr>
                        <?php $i=1; $total=0; foreach($transactions as $trx): $total+=$trx->amount; ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <?= $trx->schoolPayment->name == 'SPP' ? $trx->schoolPayment->name.' Bulan '.$trx->invoice->month : $trx->schoolPayment->name ?>
                            </td>
                            <td><?= $trx->payment_method ?></td>
                            <td><?= Strings::getRupiah($trx->invoice->amount-$trx->invoice->paid_amount) ?></td>
                            <td><?= $trx->amountRupiah ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" align="right"><strong>Total</strong></td>
                            <td><?= Strings::getRupiah($total) ?></td>
                        </tr>
                    </table>
                    <br>
                    <?php if ($transaction->schoolPayment->paymentType->name !== 'MONTHLY') { ?>
                    <table width="100%" style="font-family: monospace;">
                        <tr>
                            <td><strong>Sisa Pembayaran</strong></td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" cellspacing="0" cellpadding="5" class="table table-bordered" style="font-family: monospace;">
                        <tr class="border-top">
                            <th>No</th>
                            <th>Sisa Pembayaran</th>
                            <th>Nominal</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>
                                <?= $transaction->schoolPayment->name ?>
                            </td>
                            <td><?= Strings::getRupiah($transaction->invoice->amount-$transaction->amount) ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><strong>Total</strong></td>
                            <td><?= Strings::getRupiah($transaction->invoice->amount-$transaction->amount) ?></td>
                        </tr>
                    </table>
                    <br>
                    <?php } ?>
                    <table width="100%" style="font-family: monospace;">
                        <tr>
                            <td width="50%"></td>
                            <td>
                                <?= $transaction->invoice->school->city.', '.date('d/m/Y', strtotime($transaction->date)) ?><br>
                                Petugas<br><br><br>
                                <?= $transaction->user->name ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
}

function terbilang($nilai) {
    if($nilai<0) {
        $hasil = "minus ". trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }           
    return $hasil;
}
?>