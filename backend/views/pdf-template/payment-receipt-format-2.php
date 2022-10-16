<?php
use common\helpers\Strings;
use common\models\Transaction;

$transactionNumber = '';
$transactionDate = '';
foreach($transactions as $trx) {
    $transactionNumber = $trx->number;
    $transactionDate = Strings::getFullDate($trx->date);
    break;
}
$baseUrl = Yii::$app->request->baseUrl;
?>
<div style="color: #000000;">
    
    <!-- <watermarkimage src="images/watermark-sertifikat-provinsi.png" size="285, 200" alpha="1" /> -->
    <br><br><br><br><br><br><br>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table width="100%">
                        <tr>
                            <td class="small"><strong>NIS</strong></td>
                            <td>:</td>
                            <td class="small monospacearial"><?= $transaction->student->nis ?></td>
                            <td></td>
                            <td class="small"><strong>No Transaksi</strong></td>
                            <td>:</td>
                            <td class="small monospacearial"><?= $transactionNumber ?></td>
                        </tr>
                        <tr>
                            <td class="small"><strong>Nama</strong></td>
                            <td>:</td>
                            <td class="small monospacearial"><?= $transaction->student->name ?></td>
                            <td></td>
                            <td class="small"><strong>Tanggal</strong></td>
                            <td>:</td>
                            <td class="small monospacearial"><?= $transactionDate ?></td>
                        </tr>
                        <tr>
                            <td class="small"><strong>Kelas</strong></td>
                            <td>:</td>
                            <td class="small monospacearial"><?= $transaction->student->class ?></td>
                            <td></td>
                            <td class="small"><strong>Tahun Pelajaran</strong></td>
                            <td>:</td>
                            <td class="small monospacearial"><?= $transaction->student->year ?></td>
                        </tr>
                    </table>
                    <span class="large">Dengan rincian pembayaran sebagai berikut:</span>
                    <!-- <br> -->
                    <table width="100%" cellspacing="0" cellpadding="5" class="table table-bordered">
                        <tr class="border-top" style="border: 1px solid black;">
                            <th class="small" style="border: 1px solid black;">NO</th>
                            <th class="small" style="border: 1px solid black;">NAMA PEMBAYARAN</th>
                            <th class="small" style="border: 1px solid black;">JENIS PEMBAYARAN</th>
                            <th class="small" style="border: 1px solid black;">TAGIHAN</th>
                            <th class="small" style="border: 1px solid black;">JUMLAH PEMBAYARAN</th>
                        </tr>
                        <?php
                        $i=1;
                        $total=0;
                        foreach($transactions as $trx):
                            $total+=$trx->amount;

                            // get total kekurangan pada sebelum transaksi ini
                            $paidTotal = 0;
                            $oldTrxs = Transaction::find()->where(['invoice_id' => $trx->invoice_id])->orderBy(['id' => SORT_ASC])->all();
                            foreach ($oldTrxs as $oldTrx) {
                                if ($oldTrx->id == $trx->id) {
                                    break;
                                }
                                $paidTotal += $oldTrx->amount;
                            }
                            ?>
                            <tr style="border: 1px solid black;">
                                <td rowspan="2" class="small monospacearial" style="vertical-align: top; border: 1px solid black;"><?= $i++ ?></td>
                                <td rowspan="2" class="small monospacearial" style="vertical-align: top; border: 1px solid black;"><?= $trx->schoolPayment->name == 'SPP' ? $trx->schoolPayment->name.' Bulan '.$trx->invoice->month : $trx->schoolPayment->name ?></td>
                                <td rowspan="2" class="small monospacearial" style="vertical-align: top; text-align: center; border: 1px solid black;"><?= $trx->payment_method == 'CASH' ? 'TUNAI' : $trx->payment_method ?></td>
                                <td class="small monospacearial">
                                    <?= Strings::getRupiah($trx->invoice->amount-$paidTotal) ?>
                                </td>
                                <td rowspan="2" class="small monospacearial" style="vertical-align: top; border: 1px solid black;"><?= $trx->amountRupiah ?></td>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <td class="small monospacearial">
                                    <?= Strings::getRupiah(($trx->invoice->amount-$paidTotal)-$trx->amount) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="border: 1px solid black;">
                            <td colspan="3" style="border: 1px solid black;"><strong>Catatan: </strong><?= $transaction->note ?></td>
                            <td align="right" class="small" style="border: 1px solid black;"><strong>Total</strong></td>
                            <td class="small monospacearial"><?= Strings::getRupiah($total) ?></td>
                        </tr>
                    </table>
                    <!-- <br> -->
                    <table width="100%">
                        <tr>
                            <td width="70%"></td>
                            <td class="small">
                                <?= $transaction->invoice->school->city.', '.Strings::getFullDate($transaction->date) ?><br>
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