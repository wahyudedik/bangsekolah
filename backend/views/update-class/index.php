<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Pindah Kelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['action' => ['update-class/create', 'fromYear' => $year, 'fromClass' => $class], 'method' => 'post']) ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Dari Kelas</h5>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="input-group input-group-sm">
                                <label style="width: 60px;">Tahun </label>
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
                                <label style="width: 60px;">Kelas </label>
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
                        <div class="col-lg-4">
                            <button name="search" class="btn btn-primary" id="btn-search">
                                <i class="fas fa-search"></i> Tampilkan
                            </button>
                        </div>
                    </div>
            </div>
        </div>
    
        <?php if ($year && $class): ?>
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Siswa Kelas <?= $class ?></h5>
                    <p style="margin-bottom: 0px;">Tahun Ajaran <?= $year ?></p>
                </div>
                <div class="card-body p-0" id="card-students">
                    <?php if ($students): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check-all">
                                        </th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $stdn) : ?>
                                        <?php if ($stdn->unpaidInvoiceCount == 0): ?>
                                            <tr>
                                                <td><input type="checkbox" name="student[]" class="check-student" value="<?= $stdn->id ?>"></td>
                                                <td><?= $stdn->nis ?></td>
                                                <td><?= $stdn->nisn ?></td>
                                                <td><?= $stdn->name ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td><input type="checkbox" disabled></td>
                                                <td class="text-danger"><?= $stdn->nis ?></td>
                                                <td class="text-danger"><?= $stdn->nisn ?></td>
                                                <td class="text-danger"><?= $stdn->name ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="callout callout-warning" style="margin: 20px;">
                            <p>Tidak ada siswa di kelas ini</p>
                        </div>                        
                    <?php endif; ?>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="input-group input-group-sm">
                                <label style="width: 60px;">Tahun </label>
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
                                <label style="width: 60px;">Kelas </label>
                                <select id="next-class" class="form-control" name="next-class">
                                    <option value="">-- Pilih kelas --</option>
                                    <?php 
                                    foreach ($classes as $cls) {
                                        echo '<option value="'.$cls->name.'">'.$cls->name.'</option>';
                                    }
                                    ?>
                                    <option value="lulus">LULUS</option>
                                </select>
                            </div><br>
                        </div>
                        <div class="col-lg-4">
                            <?= Html::submitButton('<i class="fas fa-paper-plane"></i> Proses', [
                                'class' => 'btn btn-success',
                                'id' => 'btn-submit',
                                'name' => 'submit',
                                'style' => 'display: none;'
                            ]) ?>
                        </div>
                    </div>
                
            </div>
        </div>  
    </div>
</div>
<?php ActiveForm::end() ?>

<script type="text/javascript">

    // check if form is valid
    function checkValidation() {
        if (
            $('#next-year option:selected').val() !== '' &&
            $('#next-class option:selected').val() !== ''
        ) {
            $('#btn-submit').show();
        } else {
            $('#btn-submit').hide();
        }
    }

    // redirect url
    $('#btn-search').click(function(){
        window.location.replace('<?= Url::to(['update-class/index']) ?>&year='+$('#from-year').val()+'&class='+$('#from-class').val()+'');
    });

    // check all students
    $('#check-all').click(function(){
        if ($('#check-all').is(':checked')) {
            $('.check-student').attr('checked', true);
        } else {
            $('.check-student').attr('checked', false);
        }
    });

    // submit button
    var submitClicked = false;
    $("#btn-submit").click(function(e){
        if (submitClicked == false) {
            if (confirm('Apakah Anda yakin simpan data kelas siswa?')) {
                $('#w0').submit();
                setTimeout(function(){
                    $('#btn-submit').trigger('click');
                }, 500)
            }
        }
        submitClicked = true;
    });

    $('#next-year').change(function(){
        checkValidation();
    });

    $('#next-class').change(function(){
        checkValidation();
    });

</script>
