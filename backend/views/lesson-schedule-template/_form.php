<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\SchoolClass;
use common\models\Day;
use common\models\Teacher;
use common\models\Lesson;
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'class')->dropDownList(SchoolClass::getList()) ?>
                <?= $form->field($model, 'day')->dropDownList(
                    Day::getList()
                    // [
                    // 'Senin' => 'Senin',
                    // 'Selasa' => 'Selasa',
                    // 'Rabu' => 'Rabu',
                    // 'Kamis' => 'Kamis',
                    // 'Jumat' => 'Jumat',
                    // 'Sabtu' => 'Sabtu',
                    // 'Minggu' => 'Minggu',
                    // ]
                ) ?>
                <?= $form->field($model, 'hour')->dropDownList([
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ]) ?>
                <?= $form->field($model, 'teacher_id')->dropDownList(Teacher::getList()) ?>
                <?= $form->field($model, 'lesson_id')->dropDownList(Lesson::getList()) ?>
                <div class="form-group">
                    <?= Html::a('<i class="fas fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-warning']) ?>
                    <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>