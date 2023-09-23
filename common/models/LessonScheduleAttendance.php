<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lesson_schedule_attendance".
 *
 * @property int $id
 * @property int $lesson_schedule_id
 * @property int $student_id
 * @property string $status MASUK, IZIN, SAKIT, ABSEN
 * @property string $datetime
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LessonSchedule $lessonSchedule
 * @property Student $student
 */
class LessonScheduleAttendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_schedule_attendance';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                 ],
                 'value' => new \yii\db\Expression('NOW()'),
             ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_schedule_id', 'student_id'], 'integer'],
            [['datetime', 'created_at', 'updated_at'], 'safe'],
            [['note'], 'string'],
            [['status'], 'string', 'max' => 255],
            [['lesson_schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => LessonSchedule::className(), 'targetAttribute' => ['lesson_schedule_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['student_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_schedule_id' => 'Lesson Schedule ID',
            'student_id' => 'Student ID',
            'status' => 'Status',
            'datetime' => 'Datetime',
            'note' => 'Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonSchedule()
    {
        return $this->hasOne(LessonSchedule::className(), ['id' => 'lesson_schedule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }
}
