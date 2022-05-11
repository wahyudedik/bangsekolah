<?php

namespace common\models;

use yii\helpers\Html;
use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "program_content".
 *
 * @property int $id
 * @property int $program_id
 * @property string $title
 * @property string $slug
 * @property string $image
 * @property string $id_content
 * @property string $en_content
 * @property int $position
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Program $program
 */
class ProgramContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program_content';
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
             [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'immutable' => false,
                'ensureUnique' => true,
                'slugAttribute' => 'slug',
            ], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['program_id', 'position'], 'integer'],
            [['slug', 'id_content', 'en_content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'id']],
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
            'slug' => 'Slug',
            'id_content' => 'Id Content',
            'en_content' => 'En Content',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    public function getImageLarge()
    {
        if ($this->image) {
            $path = Yii::getAlias('@fronturl/uploads/program-content/large-'.$this->image);
            return Html::img($path, ['class' => 'img img-responsive']);
        } else {
            $path = Yii::getAlias('@fronturl/images/default-post.jpg');
            return Html::img($path, ['class' => 'img img-responsive']);
        }
    }
}
