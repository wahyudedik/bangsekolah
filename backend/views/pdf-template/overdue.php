<?php
use common\models\Invoice;
use common\helpers\Strings;
$baseUrl = Yii::$app->request->baseUrl;
?>

<?php if ($students): ?>
    <div class="card">
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
        <div class="card-body p-0">
            <h4 align="center"><b>Laporan Tunggakan Siswa</b></h4>
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
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <?php
                    $stop = false;
                    foreach ($schoolMonths as $mnth) {
                        if ($stop) break;
                        if ($mnth->name == $month) $stop = true;

                        echo '<th style="min-width: 80px;">'.$mnth->name.'</th>';
                    }
                    ?>
                    <th>Total Tunggakan</th>
                </tr>
                <?php
                $i=1;
                foreach ($students as $student):
                    $totalOverdue = 0;

                    // check if overdue > 0
                    $stop = false;
                    foreach ($schoolMonths as $mnth) {
                        if ($stop) break;
                        if ($mnth->name == $month) $stop = true;
                        $invoice = Invoice::find()->where(['student_id' => $student->id, 'school_payment_id' => $payment->id, 'month' => $mnth->name])->one();
                        if ($invoice && ($invoice->amount > $invoice->paid_amount)) {
                            $totalOverdue += ($invoice->amount-$invoice->paid_amount);
                        }
                    }
                    if ($totalOverdue > 0): 
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $student->nisn ?></td>
                            <td><?= $student->name ?></td>
                            <?php
                            $nextStop = false;
                            $totalOverdue = 0;
                            foreach ($schoolMonths as $mnth) {
                                if ($nextStop) break;
                                if ($mnth->name == $month) $nextStop = true;

                                $invoice = Invoice::find()->where(['student_id' => $student->id, 'school_payment_id' => $payment->id, 'month' => $mnth->name])->one();
                                if ($invoice && ($invoice->amount > $invoice->paid_amount)) {
                                    $totalOverdue += ($invoice->amount-$invoice->paid_amount);
                                    echo '<td class="text-danger">'.Strings::getRupiah($invoice->amount-$invoice->paid_amount).'</td>';
                                } else {
                                    echo '<td class="text-success">Lunas</td>';
                                }
                            }
                            ?>
                            <td><?= Strings::getRupiah($totalOverdue) ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
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
    </div>
<?php else: ?>
    <div class="callout callout-info">
        Pilih tahun ajaran, kelas, dan jenis pembayaran
    </div>
<?php endif; ?>