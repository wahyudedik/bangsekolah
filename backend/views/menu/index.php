<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\User;

$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-tools float-right">
                    <?php
                    if (User::can('menu', 'create'))
                        echo Html::a('<i class="fas fa-plus-circle fa-lg"></i> Create Menu', ['create'], ['class' => 'btn btn-success pull-right']);
                    ?>
                    <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary pull-right']) ?> 
                </div>
            </div>
            
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NUMBER</th>
                            <th width="350px">MENU</th>
                            <th>CONTROLLER</th>
                            <th width="300px">ACTIONS</th>
                            <th>DEFAULT</th>
                            <th>VISIBILITY</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataProvider->models as $model) {
                            recursive($model);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info">
                <strong>CATATAN:</strong> 
                Pengaturan menu ini diinput oleh programmer karena name action harus disesuaikan dengan method yang sudah dibuat secara hardcode. Daftar menu ini hanya perlu diupdate jika ada perubahan atau penambahan fitur.
            </div>
        </div>
    </div>
</div>

<?php
function recursive($model)
{
    static $counter = 1;
    echo '<tr>';
    echo '<td>'.$counter++.'</td>';
    echo '<td>';
        // for ($i=0; $i<$model->level; $i++) echo '--------';
        if ($model->level > 1) echo '<span style="padding-left: 30px;"> -></span> ';
        echo ' '.$model->iconRaw.' '.$model->name;
    echo '</td>';
    echo '<td>'.$model->controller.'</td>';
    echo '<td>'.$model->actionRaw.'</td>';
    echo '<td>'.$model->default_action.'</td>';
    echo '<td>'.$model->visibilityLabel.'</td>';
    echo '<td width="150px">';
        if (User::can('menu', 'view'))
            echo Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn btn-success']).' ';
        if (User::can('menu', 'update'))
            echo Html::a('<i class="fas fa-pen"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
        $deleteButton = true;
        if (count($model->childs) > 0) {
            $deleteButton = false;
        } else if (count($model->roleMenus) > 0) {
            $deleteButton = false;
        }
        if ($deleteButton) {
            if (User::can('menu', 'delete'))
                echo Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this item?', 'data-method' => 'post']);
        }
    echo '</td>';

    $childs = $model->childs;
    foreach ($childs as $model) {
        recursive($model);
    }
}
?>