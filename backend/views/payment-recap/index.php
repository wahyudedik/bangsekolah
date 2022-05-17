<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Invoice;
use common\helpers\Strings;

$this->title = 'Rekapitulasi Pembayaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <h5>Rekapitulasi Pembayaran</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
        <div class="row">
            <div class="col-lg-2">
                <label>Tahun Ajaran</label><br>
                <select class="form-control" name="year">
                    <option value="">-- Pilih tahun --</option>
                    <?php 
                    foreach ($years as $yr) {
                        echo '<option '.($yr->name == $year ? 'selected' : '').' value="'.$yr->name.'">'.$yr->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2">
                <label>Kelas</label><br>
                <select class="form-control" name="class">
                    <option value="">-- Pilih kelas --</option>
                    <?php 
                    foreach ($schoolClasses as $cls) {
                        echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label>Pembayaran</label><br>
                <select class="form-control" name="payment">
                    <option value="">-- Pilih pembayaran --</option>
                    <?php 
                    foreach ($schoolPayments as $pymnt) {
                        echo '<option '.($pymnt->number == ($payment ? $payment->number : '') ? 'selected' : '').' value="'.$pymnt->number.'">'.$pymnt->name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2">
                <br>
                <?= Html::submitButton('<i class="fas fa-search"></i> Tampilkan', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-sync-alt"></i> Reset', ['transaction/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

<?php if ($students): ?>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered">
            <tr>
                <th>No</th>
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
                        <td><?= $student->name ?></td>
                        <td><?= $invoice->amountRupiah ?></td>
                        <td><?= $invoice->paidAmountRupiah ?></td>
                        <td><?= Strings::getRupiah($invoice->amount - $invoice->paid_amount) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php endif; ?>