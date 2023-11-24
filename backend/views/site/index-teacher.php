<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\LessonScheduleTemplate;
use common\models\SchoolClass;

$this->title = 'Beranda';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->identity;
?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['index']), 'options' => ['class' => 'form-inline', 'id' => 'form-search']]) ?>
                <div class="input-group input-group-sm">
                    <?= $form->field($model, 'class')->dropDownList(SchoolClass::getList(), ['name' => 'class']) ?>
                </div>
                <?php ActiveForm::end() ?>
            </div>
            <div class="col-lg-6">
                <div class="card-tools float-right">
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
                    <th>Jam</th>
                    <th>Guru</th>
                    <th>Pelajaran</th>
                    <th>Action</th>
                </tr>
                <?php
                $no = 1;
                foreach ($days as $day) {
                    $templates = LessonScheduleTemplate::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'class' => $class, 'day' => $day->name])->all();
                    if (count($templates) > 0) {
                        $lessonNum = 1;
                        foreach ($templates as $template) {
                            echo '<tr>';
                            if ($lessonNum == 1) {
                                echo '<td rowspan="' . count($templates) . '">' . $no . '</td>';
                                echo '<td rowspan="' . count($templates) . '">' . $day->name . '</td>';
                            }
                            // echo '<td>' . $template->class . '</td>';
                            echo '<td>' . $template->hour . '</td>';
                            echo '<td>' . $template->teacher->name . '</td>';
                            echo '<td>' . $template->lesson->name . '</td>';
                            if ($template->teacher->user_id == $user->id) {
                                echo '<td>' . Html::a('Masuk Kelas <i class="fas fa-chalkboard-teacher"></i>', ['lesson-schedule/take', 'id' => $template->id], ['class' => 'btn btn-primary']) . '</td>';
                            } else {
                                echo '<td></td>';
                            }
                            echo '</tr>';
                            $lessonNum++;
                        }
                    } else {
                        echo '<tr>';
                        echo '<td>' . $no . '</td>';
                        echo '<td>' . $day->name . '</td>';
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

<style>
    .card-body.p-0 .table tbody>tr>td:first-of-type,
    .card-body.p-0 .table tbody>tr>th:first-of-type,
    .card-body.p-0 .table tfoot>tr>td:first-of-type,
    .card-body.p-0 .table tfoot>tr>th:first-of-type,
    .card-body.p-0 .table thead>tr>td:first-of-type,
    .card-body.p-0 .table thead>tr>th:first-of-type {
        padding-left: 5px;
    }

    .control-label {
        margin-right: 10px;
    }
</style>

<script>
    $('#lessonscheduletemplate-class').change(function() {
        this.form.submit();
    });
</script>