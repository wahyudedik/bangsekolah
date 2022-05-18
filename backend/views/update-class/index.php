<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Naik Kelas';
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
                </div>
                <div class="card-body p-0" id="card-body-hidden">
                    
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
                                    <option value="lulus">LULUS</option>
                                </select>
                            </div><br>
                        </div>
                        <div class="col-lg-6">
                            <?= Html::submitButton('<i class="fas fa-upload"></i> Proses', ['class' => 'btn btn-success', 'id' => 'btn-submit', 'name' => 'submit']) ?>
                        </div>
                    </div>
                
            </div>
        </div>  
    </div>
</div>
<?php ActiveForm::end() ?>
<script type="text/javascript">
        $("#card-body-hidden").hide();

        if (
            $('#from-year option:selected').val() !== '' &&
            $('#from-class option:selected').val() !== ''
        ) {
            $("#card-body-hidden").show();
        }
        // $("#btn-search").click(function(){
        //     $("#card-body-hidden").show();
        // });

        $("#btn-search").click(function(){
            window.location.replace('<?= Url::to(['update-class/index']) ?>&year='+$('#from-year').val()+'&class='+$('#from-class').val()+'');
        });
</script>
