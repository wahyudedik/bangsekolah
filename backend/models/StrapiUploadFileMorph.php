<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "upload_file_morph".
 *
 * @property int $id
 * @property int $upload_file_id
 * @property int $related_id
 * @property string $related_type
 * @property string $field
 * @property int $order
 */
class StrapiUploadFileMorph extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload_file_morph';
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
            [['id', 'upload_file_id', 'related_id', 'order'], 'integer'],
            [['related_type'], 'string', 'max' => 13],
            [['field'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'upload_file_id' => 'Upload File ID',
            'related_id' => 'Related ID',
            'related_type' => 'Related Type',
            'field' => 'Field',
            'order' => 'Order',
        ];
    }
}
