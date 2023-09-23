<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lesson_schedule".
 *
 * @property int $id
 * @property int $school_id
 * @property int $school_class_id
 * @property string $date
 * @property string $lesson_hour
 * @property int $teacher_id
 * @property int $lesson_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property School $school
 * @property Teacher $teacher
 * @property Lesson $lesson
 * @property SchoolClass $schoolClass
 * @property LessonScheduleAttendance[] $lessonScheduleAttendances
 */
class LessonSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'school_class_id', 'teacher_id', 'lesson_id', 'created_by', 'updated_by'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['lesson_hour', 'status'], 'string', 'max' => 255],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => School::className(), 'targetAttribute' => ['school_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::className(), 'targetAttribute' => ['lesson_id' => 'id']],
            [['school_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolClass::className(), 'targetAttribute' => ['school_class_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'school_class_id' => 'School Class ID',
            'date' => 'Date',
            'lesson_hour' => 'Lesson Hour',
            'teacher_id' => 'Teacher ID',
            'lesson_id' => 'Lesson ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lesson::className(), ['id' => 'lesson_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolClass()
    {
        return $this->hasOne(SchoolClass::className(), ['id' => 'school_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonScheduleAttendances()
    {
        return $this->hasMany(LessonScheduleAttendance::className(), ['lesson_schedule_id' => 'id']);
    }
}
