<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

$this->title = 'Menu '.$role->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <header class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                        <select class="form-control" name="role" id="select-role">
                            <?php
                            foreach (\common\models\Role::find()->all() as $userRole) {
                                if ($role->id == $userRole->id) {
                                    echo '<option selected="selected" value="'.$userRole->id.'">'.$userRole->name.'</option>';
                                } else {
                                    echo '<option value="'.$userRole->id.'">'.$userRole->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                        <?php  ActiveForm::end() ?>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-tools float-right">
                            <?php
                            if (User::can('role-menu', 'create'))
                                echo Html::a('<i class="fas fa-plus-circle fa-lg"></i> Create Menu', ['create', 'role' => $role->id], ['class' => 'btn btn-success pull-right']);
                            ?>
                            <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary pull-right']) ?> 
                        </div>
                    </div>
                </div>
            </header>
            <div class="card-body p-0">
                <?= $this->render('@app/views/role-menu/_grid_role_menu', ['dataProvider' => $dataProvider]) ?>
            </div>
            <div class="alert alert-info">
                <strong>CATATAN:</strong> 
                Pengaturan hak akses ini bisa dilakukan oleh Super Admin atas arahan programmer. Halaman ini idealnya diupdate sekali saja saat sistem akan berjalan untuk pertama kali. Jika hak akses menu sudah di-assign ke masing-masing jenis role, sistem akan berjalan sebagaimana mestinya dan tidak perlu mengubah-ubah pengaturan ini.
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#select-role').change(function(){
        this.form.submit();
    });
</script>