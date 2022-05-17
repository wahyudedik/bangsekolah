<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Naik Kelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index'])]) ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Dari Kelas</h5>
                
                <div class="col-lg-6">
                    <div class="input-group input-group-sm">
                        <label style="margin-right: 5px">Tahun </label>
                        <select id="from-year" class="form-control" name="from-year">
                            <option value="">- - Pilih Tahun - -</option>
                            <?php 
                            foreach ($years as $yrs) {
                                echo '<option '.($yrs->name == $year ? 'selected' : '').' value="'.$yrs->name.'">'.$yrs->name.'</option>';
                            }
                            ?>
                        </select>
                    </div><br>
                    <div class="input-group input-group-sm">
                        <label style="margin-right: 5px">Kelas </label>
                        <select id="from-class" class="form-control" name="from-class">
                            <option value="">- - Pilih Kelas - -</option>
                            <?php 
                            foreach ($classes as $cls) {
                                echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                            }
                            ?>
                        </select>
                    </div><br>
                    
                </div>
                
            </div>
            <div class="card-body" id="card-body-hidden">
                <div class="table-responsive" style="padding-top: 10px">
                    <h6>Daftar Siswa</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Ke Kelas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <div class="col-lg-6">
                        <div class="input-group input-group-sm">
                            <label style="margin-right: 5px">Tahun </label>
                            <select id="next-year" class="form-control" name="next-year">
                                <option value="">- - Pilih Tahun - -</option>
                                <?php 
                                foreach ($years as $yrs) {
                                    echo '<option '.($yrs->name == $year ? 'selected' : '').' value="'.$yrs->name.'">'.$yrs->name.'</option>';
                                }
                                ?>
                            </select>
                        </div><br>
                        <div class="input-group input-group-sm">
                            <label style="margin-right: 5px">Kelas </label>
                            <select id="next-class" class="form-control" name="next-class">
                                <option value="">- - Pilih Kelas - -</option>
                                <?php 
                                foreach ($classes as $cls) {
                                    echo '<option '.($cls->name == $class ? 'selected' : '').' value="'.$cls->name.'">'.$cls->name.'</option>';
                                }
                                ?>
                            </select>
                        </div><br>
                            <div class="input-group input-group-sm">
                            <?= Html::submitButton('<i class="fas fa-search"></i> Terapkan', ['class' => 'btn btn-success', 'id' => 'btn-submit']) ?>
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>  
    </div>
</div>
<?php ActiveForm::end() ?>
<script type="text/javascript">

    function checkValidation() {
        if (
            $('#from-year option:selected').val() !== '' &&
            $('#from-class option:selected').val() !== ''
        ) {
            $('#btn-submit').show();
        } else {
            $('#btn-submit').hide();
        }
    }
</script>
