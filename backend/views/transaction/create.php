<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Strings;

$this->title = 'Transaksi Baru';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(['action' => ['transaction/create'], 'method' => 'get']) ?>
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" style="text-align: right;">NISN</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="nisn" value="<?= $nisn ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" style="text-align: right;">NAMA SISWA</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" value="<?= $name ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-search"></i> Lihat Tagihan</button>
                    <?= Html::a('<i class="nav-icon fas fa-sync-alt"></i> Refresh', ['transaction/create'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
<?php if ($student): ?>
    <div class="card">
        <div class="card-header">
            <h5>Daftar Tagihan Untuk <?= $student->name ?></h5>
            NIS = <?= $student->nis ?>, NISN = <?= $student->nisn ?>, Kelas = <?= $student->class ?>, Tahun Ajaran = <?= $student->year ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Nama Pembayaran</th>
                    <th>Bulan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Bayar</th>
                </tr>
                <?php $i=1; foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $invoice->schoolPayment->name ?></td>
                        <td><?= $invoice->month ?></td>
                        <td><?= Strings::getRupiah($invoice->amount) ?></td>
                        <td><?= $invoice->status ?></td>
                        <td>
                            <?php if ($invoice->status == 'UNPAID'): ?>
                                <input type="checkbox" name="invoice[]" class="check-invoice" id="invoice-<?= $invoice->id ?>" value="<?= $invoice->id ?>">
                                <input type="text" name="amount[]" id="amount-<?= $invoice->id ?>" value="<?= $invoice->amount ?>">
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>Detail Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <label>Total Bayar</label>
                    <input type="text" id="total" class="form-control" name="total" value="0" style="font-size: 25px; height: 60px;">
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style type="text/css">
.form-group {
    margin-bottom: 0px;
}
</style>

<script type="text/javascript">
    $('.check-invoice').change(function() {
        var amount = parseInt($('#amount-'+$(this).val()).val());
        var total = parseInt($('#total').val());
        if(this.checked) {
            total += amount;
        } else {
            total -= amount;            
        }
        $('#total').val(total);
    });
</script>