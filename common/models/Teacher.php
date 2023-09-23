<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property int $id
 * @property int $school_id
 * @property string $nik
 * @property string $name
 * @property string $gender
 * @property string $class
 * @property string $year
 * @property string $status ACTIVE, NONACTIVE
 * @property string $created_at
 * @property string $updated_at
 *
 * @property LessonSchedule[] $lessonSchedules
 */
class Teacher extends \yii\db\ActiveRecord
{
    public $file;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher';
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
            [['school_id'], 'integer'],
            [['created_at', 'updated_at', 'file'], 'safe'],
            [['nik', 'name', 'gender', 'class', 'year', 'status'], 'string', 'max' => 255],
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
            'nik' => 'NIK',
            'name' => 'Nama Lengkap',
            'gender' => 'Gender',
            'class' => 'Wali Kelas',
            'year' => 'Tahun Ajaran',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonSchedules()
    {
        return $this->hasMany(LessonSchedule::className(), ['teacher_id' => 'id']);
    }
}
