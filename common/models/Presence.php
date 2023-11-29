<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "presence".
 *
 * @property int $id
 * @property int $school_id
 * @property int $student_id
 * @property string $class
 * @property string $date
 * @property string $check_in
 * @property string $check_out
 * @property string $status_check_in ONTIME, LATE
 * @property string $status_check_out ONTIME, EARLY
 * @property string $created_at
 * @property string $updated_at
 *
 * @property School $school
 * @property Student $student
 */
class Presence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presence';
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
            [['school_id', 'student_id'], 'integer'],
            [['date', 'check_in', 'check_out', 'created_at', 'updated_at'], 'safe'],
            [['class', 'status_check_in', 'status_check_out'], 'string', 'max' => 255],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => School::className(), 'targetAttribute' => ['school_id' => 'id']],
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
            'school_id' => 'School ID',
            'student_id' => 'Student ID',
            'class' => 'Class',
            'date' => 'Date',
            'check_in' => 'Check In',
            'check_out' => 'Check Out',
            'status_check_in' => 'Status Check In',
            'status_check_out' => 'Status Check Out',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }
}
