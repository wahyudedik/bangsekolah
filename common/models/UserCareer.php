<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "user_career".
 *
 * @property int $id
 * @property int $user_id
 * @property int $career_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property Career $career
 */
class UserCareer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_career';
    }

    public function behaviors()
    {
        return [
            // TimestampBehavior::className(),
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
            [['user_id', 'career_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['career_id'], 'exist', 'skipOnError' => true, 'targetClass' => Career::className(), 'targetAttribute' => ['career_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'career_id' => 'Career ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'statusString' => 'Status',
            'userLink' => 'Member Name',
            'careerLink' => 'Job Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCareer()
    {
        return $this->hasOne(Career::className(), ['id' => 'career_id']);
    }

    public function getUserLink()
    {
        return Html::a($this->user->name, ['member/view', 'id' => $this->user_id]);
    }

    public function getCareerLink()
    {
        return Html::a($this->career->title, ['career/view', 'id' => $this->career_id]);
    }

    public function getStatusString()
    {
        switch ($this->status) {
            case 0:
                return 'PENDING';
                break;
            case 1:
                return 'READ';
                break;
            
            default:
                return 'PENDING';
                break;
        }
    }

}
