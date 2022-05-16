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
    <?php $form = ActiveForm::begin(['action' => ['transaction/create'], 'method' => 'post']) ?>
        <div class="card">
            <div class="card-header">
                <h5>Detail Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <label>Total Bayar</label>
                        <input type="text" id="label-total" class="form-control" name="total" value="0" readonly style="font-size: 25px; height: 60px;">
                        <input type="hidden" id="total" class="form-control" name="total" value="0">
                        <br>
                        <button type="submit" name="submit" class="btn btn-success"><i class="nav-icon fas fa-save"></i> Simpan Transaksi</button>
                    </div>
                    <div class="col-lg-8">
                        <div class="callout callout-info">
                            <h5>Panduan transaksi</h5>
                            <p>There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <th>Status Bayar</th>
                        <th>Bayar</th>
                    </tr>
                    <?php $i=1; foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $invoice->schoolPayment->name ?></td>
                            <td><?= $invoice->month ?></td>
                            <td><?= Strings::getRupiah($invoice->amount) ?></td>
                            <td><?= $invoice->statusLabel ?></td>
                            <td>
                                <?php if ($invoice->status == 'UNPAID'): ?>
                                    <input type="hidden" id="amount-max-<?= $invoice->id ?>" value="<?= $invoice->amount ?>">
                                    <input type="checkbox" name="invoice[]" class="check-invoice" id="invoice-<?= $invoice->id ?>" value="<?= $invoice->id ?>">
                                    <input type="number" name="amount[]" class="amount" id="amount-<?= $invoice->id ?>" value="<?= ($invoice->amount-$invoice->paid_amount) ?>" max="<?= ($invoice->amount-$invoice->paid_amount) ?>" disabled="disabled">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php ActiveForm::end() ?>
<?php endif; ?>

<style type="text/css">
.form-group {
    margin-bottom: 0px;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}
</style>

<script type="text/javascript">

    function formatRupiah(number) {
        var format = number.toString().split('').reverse().join('');
        var convert = format.match(/\d{1,3}/g);
        var rupiah = 'Rp ' + convert.join('.').split('').reverse().join('');
        return rupiah;
    }

    $('.check-invoice').change(function() {
        var amount = parseInt($('#amount-'+$(this).val()).val());
        var total = parseInt($('#total').val());
        if(this.checked) {
            total += amount;
            $('#amount-'+$(this).val()).prop('disabled', false);
        } else {
            total -= amount;            
            $('#amount-'+$(this).val()).prop('disabled', true);
        }
        $('#label-total').val(formatRupiah(total));
        $('#total').val(total);
    });

    $('.amount').focusin(function(){
        $(this).data('val', $(this).val());
    });

    $('.amount').focusout(function(){
        var prev = parseInt($(this).data('val'));
        var current = parseInt($(this).val());
        var total = parseInt($('#total').val());
        total = total - prev + current;
        $('#label-total').val(formatRupiah(total));
        $('#total').val(total);
    });

</script>