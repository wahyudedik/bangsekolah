<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Setting;
use common\helpers\Strings;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "subscriber".
 *
 * @property integer $id
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 */
class Subscriber extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber';
    }

    /**
     * @timestamp
     */
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'string'],
            [['email'], 'email', 'message' => 'It looks like your email address is not a valid email'],
            [['email'], 'required', 'message' => 'Please enter your email address'],
            [['email'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
