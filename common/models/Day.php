<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "day".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property School $school
 */
class Day extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'day';
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
            [['name'], 'required'],
            [['school_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'name' => 'Nama Hari',
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

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            Day::find()->where(['school_id' => Yii::$app->session->get('school_id'), 'status' => 'ACTIVE'])->asArray()->all(),
            'name', 'name'
        );
    }
}
