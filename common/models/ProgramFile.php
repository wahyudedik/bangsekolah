<?php

namespace common\models;

use Yii;
use yii\helpers\Html;


/**
 * This is the model class for table "program_file".
 *
 * @property int $id
 * @property int $program_id
 * @property string $title
 * @property string $file
 * @property string $created_at
 * @property string $updated_at
 */
class ProgramFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program_file';
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
            [['program_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_id' => 'Program ID',
            'title' => 'Title',
            'file' => 'File',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    public function getDownload()
    {
        $path = Yii::getAlias('@fronturl').'/uploads/program-file/'.$this->file;
        // '<i class="fa fa-cloud-download"></i>'
        // if (file_exists($path)) {
            return Html::a($this->title, $path, ['download' => true]);
        // } else {
            // return 'File tidak ditemukan';
        // }
    }  
}
