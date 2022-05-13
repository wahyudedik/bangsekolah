<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "school_class".
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string $status ACTIVE, NONACTIVE
 *
 */
class SchoolClass extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_class';
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
            [['name', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Kelas',
            'status' => 'Status',
        ];
    }

    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id'])
            ->andOnCondition(['status' => 'ACTIVE']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(
            SchoolClass::find()->where(['status' => 'ACTIVE'])->asArray()->all(),
            'id', 'name'
        );
    }
}
