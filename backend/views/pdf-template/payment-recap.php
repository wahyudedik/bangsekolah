<?php
use common\models\Invoice;
use common\helpers\Strings;
?>

<?php if ($students): ?>
    <div class="card">
        <div class="card-header">
            <h3>Rekapitulasi pembayaran</h3>
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
                <table class="table table-bordered table-responsive">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <?php
                        foreach ($schoolMonths as $month) {
                            echo '<th style="min-width: 80px;">'.$month->name.'</th>';
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
    </div>
<?php else: ?>
    <div class="callout callout-info">
        Pilih tahun ajaran, kelas, dan jenis pembayaran
    </div>
<?php endif; ?>