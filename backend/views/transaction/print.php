<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->invoice->description;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <center>
                    <h4><?= Yii::$app->session->get('school_name') ?></h4>
                    Jln. Soekarno Hatta 82 Ponorogo<br>
                    0352-364888<br>
                    <hr>
                    <strong>BUKTI PEMBAYARAN</strong>
                </center>
                <div class="row">
                    <div class="col-lg-6">
                        <table width="100%">
                            <tr>
                                <td><strong>No Transaksi</strong></td>
                                <td>:</td>
                                <td>238748327432</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>:</td>
                                <td>932327883</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Siswa</strong></td>
                                <td>:</td>
                                <td>M. Arief Fakhrudin</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table width="100%">
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td>:</td>
                                <td>01/06/2022 09:00:00</td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>:</td>
                                <td>VII</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <table class="table">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembayaran</th>
                        <th>Nominal</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>SPP Bulan Januari</td>
                        <td>Rp. 100.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Zakat Idul Fitri</td>
                        <td>Rp. 30.000</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><strong>Total</strong></td>
                        <td>Rp. 130.000</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><strong>Tunai</strong></td>
                        <td>Rp. 200.000</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right"><strong>Kembali</strong></td>
                        <td>Rp. 70.000</td>
                    </tr>
                </table>
                <br>
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6">
                        Ponorogo, <?= date('d/m/Y', strtotime($model->date)) ?><br>
                        Petugas<br><br><br>
                        <?= $model->user->name ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>