<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "holiday".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property School $school
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['name', 'status'], 'string', 'max' => 255],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => School::className(), 'targetAttribute' => ['school_id' => 'id']],
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
            'name' => 'Name',
            'date' => 'Date',
            'status' => 'Status',
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
}
