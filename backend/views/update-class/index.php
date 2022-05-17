<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Naik Kelas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Dari Kelas</h5>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group input-group-sm">
                                <label style="margin-right: 5px">Tahun </label>
                                <select id="from-year" class="form-control" name="year">
                                    <option value="">-- Pilih tahun --</option>
                                    <?php 
                                    foreach ($years as $yrs) {
                                        echo '<option '.($yrs->name == $year ? 'selected' : '').' value="'.$yrs->name.'">'.$yrs->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div><br>
                            <div class="input-group input-group-sm">
                                <label style="margin-right: 5px">Kelas </label>
                                <select id="from-class" class="form-control" name="class">
                                    <option value="">-- Pilih kelas --</option>
                                    <?php 
                                    foreach ($classes as $cls) {
                                        echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div><br>
                        </div>
                        <div class="col-lg-6">
                            <?= Html::submitButton('<i class="fas fa-search"></i> Tampilkan', ['class' => 'btn btn-primary', 'id' => 'btn-search']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>

        <?php if ($year && $class): ?>
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Siswa Kelas <?= $class ?></h5>
                </div>
                <div class="card-body p-0" id="card-body-hidden">
                    <?php $form = ActiveForm::begin(['action' => ['update-class/create'], 'method' => 'post']) ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $stdn) { ?>
                                    <tr>
                                        <td><input type="checkbox" name="student[]" class="check-student" value="<?= $stdn->id ?>"></td>
                                        <td><?= $stdn->nis ?></td>
                                        <td><?= $stdn->nisn ?></td>
                                        <td><?= $stdn->name ?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Ke Kelas</h5>
            </div>            
            <div class="card-body">
                <?php $form = ActiveForm::begin(['action' => ['update-class/create'], 'method' => 'post']) ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group input-group-sm">
                                <label style="margin-right: 5px">Tahun </label>
                                <select id="next-year" class="form-control" name="next-year">
                                    <option value="">-- Pilih tahun --</option>
                                    <?php 
                                    foreach ($years as $yrs) {
                                        echo '<option value="'.$yrs->name.'">'.$yrs->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div><br>
                            <div class="input-group input-group-sm">
                                <label style="margin-right: 5px">Kelas </label>
                                <select id="next-class" class="form-control" name="next-class">
                                    <option value="">-- Pilih kelas --</option>
                                    <?php 
                                    foreach ($classes as $cls) {
                                        echo '<option value="'.$cls->name.'">'.$cls->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div><br>
                        </div>
                        <div class="col-lg-6">
                            <?= Html::submitButton('<i class="fas fa-upload"></i> Proses', ['class' => 'btn btn-success', 'id' => 'btn-submit']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>  
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#card-body-hidden").hide();

        if (
            $('#from-year option:selected').val() !== '' &&
            $('#from-class option:selected').val() !== ''
        ) {
            $("#card-body-hidden").show();
        }
    });
</script>
