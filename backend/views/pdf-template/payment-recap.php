<?php
use common\models\Invoice;
use common\helpers\Strings;
$baseUrl = Yii::$app->request->baseUrl;
?>

<?php if ($students): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-header" style="width: 100%; border-bottom: 5px solid black; margin-bottom: 25px;  ">
                <table style="width: 100%">
                    <tr>
                        <td style="width:10%">
                            <?php if ($school->image == null) { ?>
                                <img src="<?= $baseUrl ?>/images/banksekolah-logo.png" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8; width: 80px;" />
                            <?php } else { ?>
                                <img src="<?= $baseUrl ?>/uploads/school/<?= $school->image ?>" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
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
        <h4 align="center"><b>Rekapitulasi Pembayaran Siswa</b></h4>
        <div class="title-body" style="padding-bottom: 15px">
            <table>
                <tr>
                    <td>Jenis Pembayaran</td>
                    <td>:</td>
                    <td><?= $payment->name ?></td>
                </tr>
                <tr>
                    <td>Periode Tahun</td>
                    <td>:</td>
                    <td><?= $year ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td><?= $class ?></td>
                </tr>
            </table>
        </div>
        <?php if ($payment->paymentType->name !== 'MONTHLY'): ?>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Total Bayar</th>
                        <th>Sudah Bayar</th>
                        <th>Sisa</th>
                    </tr>
                    <?php
                    $i=1;
                    foreach ($students as $student):
                        // get invoices
                        $invoice = Invoice::find()->where(['student_id' => $student->id, 'school_payment_id' => $payment->id])->one();
                        if ($invoice) :
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $student->nisn ?></td>
                                <td><?= $student->name ?></td>
                                <td><?= $invoice->amountRupiah ?></td>
                                <td><?= $invoice->paidAmountRupiah ?></td>
                                <td><?= Strings::getRupiah($invoice->amount - $invoice->paid_amount) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <div class="card-body p-0">
                <table class="table table-bordered table-responsive" style="font-size: 12px">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <?php
                        foreach ($schoolMonths as $month) {
                            echo '<th style="width: 60px;">'.$month->name.'</th>';
                        }
                        ?>
                        <th>Total Bayar</th>
                        <th>Sudah Bayar</th>
                        <th>Sisa</th>
                    </tr>
                    <?php
                    $i=1;
                    foreach ($students as $student):
                        $total = 0;
                        $totalPaid = 0;
                        $remaining = 0;
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $student->nisn ?></td>
                            <td><?= $student->name ?></td>
                            <?php
                            foreach ($schoolMonths as $month) {
                                $invoice = Invoice::find()->where(['student_id' => $student->id, 'school_payment_id' => $payment->id, 'month' => $month->name])->one();
                                if ($invoice) {
                                    $total += $invoice->amount;
                                    $totalPaid += $invoice->paid_amount;
                                    if ($invoice->paid_amount > 0) {
                                        echo '<td class="text-success">'.$invoice->paidAmountRupiah.'</td>';
                                    } else {
                                        echo '<td class="text-default">'.$invoice->paidAmountRupiah.'</td>';
                                    }
                                } else {
                                    echo '<td>NO INVOICE</td>';
                                }
                            }
                            ?>
                            <td><?= Strings::getRupiah($total) ?></td>
                            <td><?= Strings::getRupiah($totalPaid) ?></td>
                            <td><?= Strings::getRupiah($total-$totalPaid) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
        <div class="footer-body">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 70%"></td>
                    <td style="width: 30%" align="center">
                        <span><?= $school->city.', '.date('d M Y') ?></span><br />
                        <span>Mengetahui</span><br /><br /><br />
                        <span>(...............)</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-info">
        Pilih tahun ajaran, kelas, dan jenis pembayaran
    </div>
<?php endif; ?>