<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Invoice;
use common\helpers\Strings;

$this->title = 'Tunggakan Bulanan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <h5>Pilih siswa dan jenis pembayaran</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
        <div class="row">
            <div class="col-lg-2">
                <label>Tahun Ajaran</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="year">
                        <option value="">-- Pilih tahun --</option>
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
                        <option value="">-- Pilih kelas --</option>
                        <?php 
                        foreach ($schoolClasses as $cls) {
                            echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <label>Pembayaran Bulanan</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="payment">
                        <option value="">-- Pilih pembayaran --</option>
                        <?php 
                        foreach ($schoolPayments as $pymnt) {
                            echo '<option '.($pymnt->number == ($payment ? $payment->number : '') ? 'selected' : '').' value="'.$pymnt->number.'">'.$pymnt->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <label>Sampai Bulan</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="month">
                        <option value="">-- Pilih bulan --</option>
                        <?php 
                        foreach ($schoolMonths as $mnth) {
                            echo '<option '.($mnth->name == $month ? 'selected' : '').' value="'.$mnth->name.'">'.$mnth->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div style="margin-top: 30px;">
                    <?= Html::submitButton('<i class="fas fa-search"></i> Tampilkan', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-sync-alt"></i> Reset', ['payment-recap/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

<?php if ($students): ?>
    <div class="card">
        <div class="card-header">
            <h5>Daftar tunggakan</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-responsive">
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

<style>
th, td {
    white-space: nowrap;
}
</style>