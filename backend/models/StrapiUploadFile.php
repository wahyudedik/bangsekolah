<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "upload_file".
 *
 * @property int $id
 * @property string $name
 * @property string $alternativeText
 * @property string $caption
 * @property string $width
 * @property string $height
 * @property string $formats
 * @property string $hash
 * @property string $ext
 * @property string $mime
 * @property string $size
 * @property string $url
 * @property string $previewUrl
 * @property string $provider
 * @property string $provider_metadata
 * @property string $created_by
 * @property string $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class StrapiUploadFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload_file';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('strapi');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['formats', 'previewUrl', 'provider_metadata'], 'string'],
            [['size'], 'number'],
            [['name'], 'string', 'max' => 116],
            [['alternativeText'], 'string', 'max' => 20],
            [['caption'], 'string', 'max' => 152],
            [['width', 'height', 'ext', 'provider'], 'string', 'max' => 5],
            [['hash'], 'string', 'max' => 120],
            [['mime'], 'string', 'max' => 24],
            [['url'], 'string', 'max' => 134],
            [['created_by', 'updated_by'], 'string', 'max' => 2],
            [['created_at', 'updated_at'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alternativeText' => 'Alternative Text',
            'caption' => 'Caption',
            'width' => 'Width',
            'height' => 'Height',
            'formats' => 'Formats',
            'hash' => 'Hash',
            'ext' => 'Ext',
            'mime' => 'Mime',
            'size' => 'Size',
            'url' => 'Url',
            'previewUrl' => 'Preview Url',
            'provider' => 'Provider',
            'provider_metadata' => 'Provider Metadata',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
