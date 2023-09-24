<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\LessonScheduleTemplate;

$this->title = 'Jadwal Pelajaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline']]) ?>
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Search ..." value="<?=   $q ?>">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat"><i class="fas fa-search search-icon"></i></button>
                    </span>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
                    <?= Html::a('<i class="fas fa-plus fa-lg"></i> Create Lesson Schedule Template', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('<i class="fas fa-sync-alt fa-lg"></i>', ['index'], ['class' => 'btn btn-primary']) ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Kelas</th>
                    <th>Jam</th>
                    <th>Guru</th>
                    <th>Pelajaran</th>
                </tr>
                <?php
                $no = 1;
                foreach ($days as $day) {
                    $templates = LessonScheduleTemplate::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'day' => $day->name])->all();
                    if (count($templates) > 0) {
                        $lessonNum = 1;
                        foreach ($templates as $template) {
                            echo '<tr>';
                            if ($lessonNum == 1) {
                                echo '<td rowspan="'.count($templates).'">'.$no.'</td>';
                                echo '<td rowspan="'.count($templates).'">'.$day->name.'</td>';
                            }
                            echo '<td>'.$template->class.'</td>';
                            echo '<td>'.$template->hour.'</td>';
                            echo '<td>'.$template->teacher->name.'</td>';
                            echo '<td>'.$template->lesson->name.'</td>';
                            echo '</tr>';
                            $lessonNum++;
                        }
                    } else {
                        echo '<tr>';
                        echo '<td>'.$no.'</td>';
                        echo '<td>'.$day->name.'</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '</tr>';
                    }
                    $no++;
                }
                ?>
            </table>
        </div>
    </div>
</div>