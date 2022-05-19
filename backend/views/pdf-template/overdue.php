<?php
use common\models\Invoice;
use common\helpers\Strings;
?>

<?php if ($students): ?>
    <div class="card">
        <div class="card-header">
            <h3>Tunggakan Siswa</h3>
        </div>
        <div class="card-body p-0">
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
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-info">
        Pilih tahun ajaran, kelas, dan jenis pembayaran
    </div>
<?php endif; ?>