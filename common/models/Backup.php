<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "backup".
 *
 * @property int $id
 * @property int $user_id
 * @property int $school_id
 * @property string $filename
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property School $school
 */
class Backup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'backup';
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
            [['user_id', 'school_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
            'school_id' => 'School ID',
            'filename' => 'Filename',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'createdAt' => 'Tanggal',
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
    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    public function getCreatedAt()
    {
        return date('d M Y', strtotime($this->created_at));
    }

    public function getDownload()
    {
        $downloadUrl = "https://banksekolah.com/backend/web/uploads/database/$this->filename";
        return Html::a($this->filename, $downloadUrl, ['class' => 'btn btn-primary btn-xs', 'download' => $this->filename]);
    }
}
