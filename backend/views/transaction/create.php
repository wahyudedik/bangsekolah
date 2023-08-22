<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Strings;

$this->title = 'Transaksi Pembayaran';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(['action' => ['transaction/create'], 'method' => 'get']) ?>
            
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group input-group-sm">
                        <label style="margin-right: 10px;">Cari siswa</label>
                        <input type="text" name="search" class="form-control" placeholder="Ketik NIS/NISN/Nama ..." value="<?= $search ?>">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                        </span>
                    </div>
                </div>
            </div>
                <!--
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" style="text-align: right;">Cari Siswa</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="search" value="<?= $search ?>" placeholder="Ketik NIS/NISN/Nama">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-search"></i></button>
                </div>
                -->
        <?php ActiveForm::end() ?>
    </div>
</div>
<?php if ($student): ?>
    <?php $form = ActiveForm::begin(['action' => ['transaction/create'], 'method' => 'post']) ?>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>NIS / NISN</th>
                                <td>:</td>
                                <td><?= $student->nis.' / '.$student->nisn ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>:</td>
                                <td><?= $student->name ?></td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>:</td>
                                <td><?= $student->class ?></td>
                            </tr>
                            <!-- <tr>
                                <th>Tahun Ajaran</th>
                                <td>:</td>
                                <td><?= $student->year ?></td>
                            </tr> -->
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="callout callout-info">
                    <h5>Panduan transaksi</h5>
                    <span>1. Periksa Detail NIS / NISN / NAMA SISWA dan Riwayat Pembayaran</span><br>
                    <span>2. Periksa Daftar Tagihan</span><br>
                    <span>3. Pilih Jenis Pembayaran: Tunai atau Transfer</span><br>
                    <span>4. Klik Centang di menu Bayar dan inputkan jumlah yang akan dibayarkan</span><br>
                    <span>5. Jika sudah benar, Pilih Simpan transaksi</span><br>
                    <span>6. Pilih OK</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Detail Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <label>Total Bayar</label>
                        <input type="text" id="label-total" class="form-control" name="total" value="0" readonly style="font-size: 25px; height: 60px;">
                        <input type="hidden" id="total" class="form-control" name="total" value="0">
                        <input type="date" id="label-date" class="form-control" name="date" style="margin-top: 10px" value="<?= date('Y-m-d') ?>">
                        <input type="text" id="label-note" class="form-control" name="note" style="margin-top: 10px" placeholder="Catatan Pembayaran">
                        <br>
                        <button name="submit" class="btn btn-success" id="btn-submit">
                            <i class="nav-icon fas fa-save"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Riwayat Pembayaran</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pembayaran</th>
                                <th>Jumlah Bayar</th>
                                <th>Cetak</th>
                            </tr>
                            <?php $i=1; foreach ($histories as $history): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= Strings::getFullDate($history->date) ?></td>
                                    <td><?= $history->schoolPayment->name ?></td>
                                    <td><?= Strings::getRupiah($history->amount) ?></td>
                                    <td><?= Html::a('<i class="fas fa-print"></i>', ['transaction/print', 'id' => $history->id], ['class' => 'btn btn-info', 'target' => '_blank']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Daftar Tagihan Untuk <?= $student->name ?></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembayaran</th>
                        <th>Tahun</th>
                        <th>Jumlah</th>
                        <th>Dibuat Pada</th>
                        <th>Status Bayar</th>
                        <th>Jenis Pembayaran</th>
                        <th>Bayar</th>
                    </tr>
                    <?php $i=1; foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $invoice->schoolPayment->name ?></td>
                            <td><?= $invoice->year ?></td>
                            <td><?= Strings::getRupiah($invoice->amount) ?></td>
                            <td><?= date('d-m-Y', strtotime($invoice->created_at)) ?></td>
                            <td><?= $invoice->statusLabel ?></td>
                            <td>
                                <?php 
                                    if ($invoice->status == 'UNPAID') {
                                        echo '<select id="proof" name="proof[]">';
                                        echo '    <option value="CASH">TUNAI</option>';
                                        echo '    <option value="TRANSFER">TRANSFER</option>';
                                        echo '</select>';
                                    } else {
                                        echo $invoice->statusBayar;
                                    } 
                                ?>
                            </td>
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

    var submitClicked = false;
    $("#btn-submit").click(function(e){
        if (parseInt($('#total').val()) > 0) {
            if (submitClicked == false) {
                if (confirm('Apakah data pembayaran di atas sudah benar?')) {
                    $('#w1').submit();
                    setTimeout(function(){
                        $('#btn-submit').trigger('click');
                    }, 500)
                }
            }
            submitClicked = true;
        } else {
            e.preventDefault();
            alert('Pilih dahulu tagihan yang akan dibayarkan');
        }
    });

</script>