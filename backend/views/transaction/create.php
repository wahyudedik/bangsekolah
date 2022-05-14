<?php
use yii\helpers\Html;

$this->title = 'Transaksi Baru';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3">
                <!-- <label>NISN</label> -->
                <!-- <input type="text" class="form-control" name="nisn"> -->

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">NISN</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="nisn">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
