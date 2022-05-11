<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "language".
 *
 * @property int $id
 * @property string $slug
 * @property string $id_content
 * @property string $en_content
 * @property string $created_at
 * @property string $updated_at
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
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
            [['slug', 'id_content', 'en_content'], 'required'],
            [['id_content', 'en_content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'id_content' => 'Id Content',
            'en_content' => 'En Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getIdContentSort()
    {
        return Strings::sortText($this->id_content, 50);
    }

    public function getEnContentSort()
    {
        return Strings::sortText($this->en_content, 50);
    }

}
