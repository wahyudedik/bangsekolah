<?php
use common\helpers\Strings;

$transactionNumber = '';
$transactionDate = '';
foreach($transactions as $trx) {
    $transactionNumber = $trx->number;
    $transactionDate = date('d/m/Y', strtotime($trx->date));
    break;
}
?>
<div style="color: #000000;">
    
    <!-- <watermarkimage src="images/watermark-sertifikat-provinsi.png" size="285, 200" alpha="1" /> -->
    <table cellpadding="5px" width="100%">
        <tr>
            <td width="100px"><img src="images/banksekolah-logo.png" width="70px" height="70px"></td>
            <td style="text-align: left; font-family: monospace;">
                <span style="font-size: 20px;"><b><?= strtoupper($transaction->invoice->school->name) ?></b></span><br>
                Jln. Soekarno Hatta 82 Ponorogo<br>
                0352-364888<br>
            </td>
        </tr>
    </table>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p align="center" style="font-family: monospace;">
                        <strong>BUKTI PEMBAYARAN</strong>
                        <br><br>
                    </p>
                    <table width="100%" style="font-family: monospace;">
                        <tr>
                            <td><strong>No Transaksi</strong></td>
                            <td>:</td>
                            <td><?= $transactionNumber ?></td>
                            <td></td>
                            <td><strong>Tanggal</strong></td>
                            <td>:</td>
                            <td><?= $transactionDate ?></td>
                        </tr>
                        <tr>
                            <td><strong>NISN</strong></td>
                            <td>:</td>
                            <td><?= $transaction->student->nisn ?></td>
                            <td></td>
                            <td><strong>Kelas</strong></td>
                            <td>:</td>
                            <td><?= $transaction->student->class ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Siswa</strong></td>
                            <td>:</td>
                            <td><?= $transaction->student->name ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" cellspacing="0" cellpadding="5" class="table table-bordered" style="font-family: monospace;">
                        <tr class="border-top">
                            <th>No</th>
                            <th>Nama Pembayaran</th>
                            <th>Nominal</th>
                        </tr>
                        <?php $i=1; $total=0; foreach($transactions as $trx): $total+=$trx->amount; ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $trx->schoolPayment->name ?></td>
                            <td><?= Strings::getRupiah($trx->amount) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" align="right"><strong>Total</strong></td>
                            <td><?= Strings::getRupiah($total) ?></td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" style="font-family: monospace;">
                        <tr>
                            <td width="50%"></td>
                            <td>
                                Ponorogo, <?= date('d/m/Y', strtotime($transaction->date)) ?><br>
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