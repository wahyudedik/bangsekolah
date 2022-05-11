<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\models\User;

$this->title = 'Navbar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Navigation Bar</h3>
                <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <div class="card-tools float-right">
                            <?php
                            if (User::can('navbar', 'create'))
                                echo Html::a('<i class="fas fa-plus-circle fa-lg"></i> Create Navbar', ['create'], ['class' => 'btn btn-success pull-right']);
                            ?>
                            <?=  Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary pull-right']) ?> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="100px">NO</th>
                            <th width="350px">MENU NAVBAR</th>
                            <th>URL</th>
                            <th>SORT</th>
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
        if ($model->level == 2) echo '<span style="padding-left: 30px;">-> ';
        if ($model->level == 3) echo '<span style="padding-left: 60px;">-> ';
        echo ' '.$model->name;
    echo '</td>';
    echo '<td>'.$model->url.'</td>';
    echo '<td width="100px">';
        if (User::can('navbar', 'up'))
            echo $model->linkPosition.' ';
    echo '</td>';
    echo '<td width="150px">';
        if (User::can('navbar', 'view'))
            echo Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn btn-success']).' ';
        if (User::can('navbar', 'update'))
            echo Html::a('<i class="fas fa-pen"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
        $deleteButton = true;
        if (count($model->childs) > 0) {
            $deleteButton = false;
        }
        if ($deleteButton) {
            if (User::can('navbar', 'delete'))
                echo Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this item?', 'data-method' => 'post']);
        }
    echo '</td>';

    $childs = $model->childs;
    foreach ($childs as $model) {
        recursive($model);
    }
}
?>