<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Invoice;
use common\models\Transaction;
use common\helpers\Strings;

$this->title = 'Rekapitulasi Pembayaran';
$this->params['breadcrumbs'][] = $this->title;
$schoolId = Yii::$app->session->get('school_id');
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <h5>Filter Data</h5>
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
                        <option value="">-- Semua --</option>
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
                        <option value="">-- Semua --</option>
                        <?php 
                        foreach ($schoolClasses as $cls) {
                            echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <label>Nama Pembayaran</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="payment">
                        <option value="">-- Semua --</option>
                        <?php 
                        foreach ($schoolPayments as $pymnt) {
                            echo '<option '.($pymnt->number == ($payment ? $payment->number : '') ? 'selected' : '').' value="'.$pymnt->number.'">'.$pymnt->name.'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <label>Jenis Transaksi</label><br>
                <div class="input-group input-group-sm">
                    <select class="form-control" name="method">
                        <option value="">-- Semua --</option>
                        <option value="CASH">Tunai</option>
                        <option value="TRANSFER">Transfer</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div style="margin-top: 30px;">
                    <?php
                    echo Html::submitButton('<i class="fas fa-search"></i> Tampilkan', ['class' => 'btn btn-success']).' ';
                    if ($year && $class && $payment) {
                        echo Html::a('<i class="fas fa-sync-alt"></i> Reset', ['payment-recap/index'], ['class' => 'btn btn-primary']).' ';
                        echo Html::a('<i class="fas fa-print"></i> Cetak', ['payment-recap/download', 'year' => $year, 'class' => $class, 'payment' => $payment->number], ['class' => 'btn btn-info', 'download' => 'payment-recap-'.$year.'-'.$class.'-'.$payment->number.'.pdf']).' ';
                        echo Html::a('<i class="fas fa-excel"></i> Export', ['payment-recap/export', 'year' => $year, 'class' => $class, 'payment' => $payment->number], ['class' => 'btn btn-info', 'download' => 'payment-recap-'.$year.'-'.$class.'-'.$payment->number.'.pdf']).' ';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Rekapitulasi pembayaran</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered">
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">NAMA SISWA</th>
                <th rowspan="2">NAMA PEMBAYARAN</th>
                <th rowspan="2">KELAS</th>
                <th colspan="2">JENIS TRANSAKSI</th>
                <th rowspan="2">JUMLAH</th>
            </tr>
            <tr>
                <th>Tunai</th>
                <th>Transfer</th>
            </tr>
            <?php
            $i=1;
            $transactions = Transaction::find()->where(['school_id' => $schoolId])
            ->orderBy(['id' => SORT_DESC])
            ->all();
            foreach ($transactions as $trans):
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $trans->student->nis ?></td>
                    <td><?= $trans->student->name ?></td>
                    <td><?= $trans->invoice->description ?></td>
                    <td><?= $trans->student->class ?></td>
                    <td><?= $trans->payment_method == 'CASH' ? $trans->amountRupiah : '' ?></td>
                    <td><?= $trans->payment_method == 'TRANSFER' ? $trans->amountRupiah : '' ?></td>
                    <td><?= $trans->amountRupiah ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">TOTAL</td>

            </tr>
        </table>
    </div>
</div>

<style>
th, td {
    white-space: nowrap;
}
</style>